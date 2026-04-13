<?php
require 'conf.php'; // Ensure this file creates a $pdo object
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(["status" => "error", "message" => "Missing ID"]);
    exit;
}

try {
    // 1. Fetch current state
    $stmt = $pdo->prepare("SELECT isShown FROM documents WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["status" => "error", "message" => "File not found"]);
        exit;
    }

    // 2. Toggle the state (if 1 then 0, if 0 then 1)
    $newState = ($row['isShown'] == 1) ? 0 : 1;

    // 3. Update the database
    $updateStmt = $pdo->prepare("UPDATE documents SET isShown = :newState WHERE id = :id");
    $updateStmt->execute([
        ':newState' => $newState,
        ':id' => $id
    ]);

    echo json_encode([
        "status" => "success",
        "id" => $id,
        "isShown" => (int)$newState
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
