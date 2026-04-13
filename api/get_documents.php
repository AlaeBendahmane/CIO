<?php
require_once 'conf.php'; // Ensure this file creates a $pdo object
require_once 'helpers.php';
session_start();
isAuthQuery();
//isAdminQuery(); wash 7ajti biha?


$user_role = $_SESSION['role'];
$user_indice = $_SESSION['id'];
header('Content-Type: application/json');

$documents = [];

// 1. Validate Input
if (!isset($_GET['ref_user_id']) || !is_numeric($_GET['ref_user_id'])) {
    http_response_code(400);
    // echo json_encode(['error' => 'Missing or invalid user reference ID.']);
    echo json_encode([
        "status" => "error",
        "message" => 'Missing or invalid user reference ID.'
    ]);
    exit();
}

$ref_user_id = (int)$_GET['ref_user_id'];

try {
    // 2. Build Query based on Role
    if ($user_role == "A") {
        $sql = "SELECT * FROM documents WHERE isDeleted=0 AND account_doc_indice = :user_id ORDER BY creationDate ASC";
    } else {
        $sql = "SELECT * FROM documents WHERE isDeleted=0 AND isShown = 1 AND account_doc_indice = :user_id ORDER BY creationDate ASC";
    }
    //  else {
    //     http_response_code(403);
    //     echo json_encode(['error' => 'Unauthorized role.']);
    //     exit();
    // }

    // 3. Prepare and Execute
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $ref_user_id]);

    // 4. Fetch Results
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $doc_id = $row['id'];
        $filename = $row['name'];
        $doc_type = strtolower($row['contentType']);

        // Determine Icon
        $icon_class = 'fa-file';
        if (strpos($doc_type, 'pdf') !== false || strpos($filename, '.pdf') !== false) $icon_class = 'fa-file-pdf';
        else if (strpos($doc_type, 'word') !== false || strpos($filename, '.doc') !== false) $icon_class = 'fa-file-word';
        else if (strpos($doc_type, 'excel') !== false || strpos($filename, '.xls') !== false) $icon_class = 'fa-file-excel';
        else if (strpos($doc_type, 'image') !== false || strpos($filename, '.jpg') !== false || strpos($filename, '.png') !== false) $icon_class = 'fa-file-image';

        $documents[$doc_id] = [
            'id' => $doc_id,
            'type' => 'document',
            'filename' => $filename,
            'icon' => $icon_class,
            'base64_data' => $row['base64'],
            'mime_type' => $row['contentType'],
            'visibility' => $row['isShown']
        ];
    }

    // echo json_encode($documents);
    echo json_encode([
        "status" => "success",
        "data" => $documents
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    // echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    echo json_encode([
        "status" => "error",
        "message" => 'Database error: ' . $e->getMessage()
    ]);
    exit();
}
