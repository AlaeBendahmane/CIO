<?php
require 'conf.php'; // Ensure this file creates a $pdo object
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

if (!empty($_FILES['file'])) {

    $ref_user_id = isset($_POST['ref_user_id']) ? intval($_POST['ref_user_id']) : null;
    if (!$ref_user_id) {
        http_response_code(400);
        echo "User ID missing";
        exit;
    }

    $files = $_FILES['file'];
    // Handle both single and multiple file uploads
    $totalFiles = is_array($files['name']) ? count($files['name']) : 1;

    try {
        // 1. Prepare ONCE outside the loop for better performance
        $sql = "INSERT INTO documents 
                (name, contentType, type_document, account_doc_indice, base64, creationDate, creationHeure) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        for ($i = 0; $i < $totalFiles; $i++) {
            $name     = is_array($files['name']) ? $files['name'][$i] : $files['name'];
            $tmpName  = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
            $type     = is_array($files['type']) ? $files['type'][$i] : $files['type'];

            if (empty($tmpName)) continue;

            $fileContent   = file_get_contents($tmpName);
            $base64Content = base64_encode($fileContent);

            $creationDate  = date("Y-m-d");
            $creationHeure = date("H:i:s");

            $ext           = pathinfo($name, PATHINFO_EXTENSION);
            $type_document = strtoupper($ext);

            // 2. Execute with an array of values (The PDO way)
            $success = $stmt->execute([
                $name,
                $type,
                $type_document,
                $ref_user_id,
                $base64Content,
                $creationDate,
                $creationHeure
            ]);

            if ($success) {
                // echo "Uploaded: $name\n";
                echo json_encode([
                    "status" => "success",
                    "message" => "Uploaded: $name\n",
                ]);
            } else {
                // echo "Failed to insert $name\n";
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed to insert $name\n"
                ]);
            }
        }

        echo "Success";
    } catch (PDOException $e) {
        http_response_code(500);
        // echo "Database Error: " . $e->getMessage();
        echo json_encode([
            "status" => "error",
            "message" => "Database Error: " . $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    // echo "No file uploaded";
    echo json_encode([
        "status" => "error",
        "message" => "No file uploaded"
    ]);
}
