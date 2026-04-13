<?php
require 'conf.php'; // Ensure this file creates a $pdo object
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();


// 1. Check for ID in either POST or JSON input
$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(["status" => "error", "message" => "Missing ID"]);
    exit;
}

try {
    // 2. Prepare the statement
    $sql = "UPDATE documents SET isDeleted = 1 WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // 3. Execute with the ID
    $stmt->execute([':id' => (int)$id]);

    // 4. Check if a row was actually changed
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "File deleted",
            "id" => $id
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Document not found or already deleted"
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}