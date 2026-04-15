<?php
require 'conf.php'; // Ensure this file creates a $pdo object
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

if (!empty($_FILES['file'])) {

    // 1. Precise ID Handling for JSON vs Single
    $user_ids = [];
    if ($_POST['sendType'] == "single") {
        $user_ids = [intval($_POST['ref_user_id'])];
    } else {
        // Decode the JSON string sent from JavaScript
        $decoded = json_decode($_POST['ref_user_ids'], true);
        $user_ids = is_array($decoded) ? array_map('intval', $decoded) : [];
    }

    if (empty($user_ids)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "No valid User IDs found"]);
        exit;
    }

    $files = $_FILES['file'];
    $totalFiles = is_array($files['name']) ? count($files['name']) : 1;

    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO documents 
                (name, contentType, type_document, account_doc_indice, base64, creationDate, creationHeure) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        for ($i = 0; $i < $totalFiles; $i++) {
            $originalName = is_array($files['name']) ? $files['name'][$i] : $files['name'];
            $tmpName      = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
            $mimeType     = is_array($files['type']) ? $files['type'][$i] : $files['type'];
            $extension    = pathinfo($originalName, PATHINFO_EXTENSION);

            if (empty($tmpName)) continue;

            // Handle Custom Title or fallback to original
            $finalName = (!empty($_POST['title']))
                ? ($_POST['title'] . '.' . $extension)
                : $originalName;

            $base64Content = base64_encode(file_get_contents($tmpName));
            $creationDate  = date("Y-m-d");
            $creationHeure = date("H:i:s");
            $type_doc      = strtoupper($extension);

            // Link current file to every user in the bucket
            foreach ($user_ids as $id) {
                $stmt->execute([
                    $finalName,
                    $mimeType,
                    $type_doc,
                    $id,
                    $base64Content,
                    $creationDate,
                    $creationHeure
                ]);
            }
        }

        $pdo->commit();
        sendBulkNotification(
            'Nouveau document',
            'Je vous ai envoyé le document: ' . $finalName . " .\n" .
                'Pour le visualiser, accédez à l’onglet "Mes documents".',
            $user_ids,
            $_SESSION['id']
        );
        echo json_encode([
            "status" => "success",
            "message" => "Upload complete: " . count($user_ids) . " users updated."
        ]);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "DB Error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Files are empty"]);
}
