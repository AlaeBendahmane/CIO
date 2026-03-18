<?php
header('Content-Type: application/json');
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

// Get the ID from the URL or JSON body
$idFiscal = $_GET['id'] ?? null;

if (!$idFiscal) {
    echo json_encode(['success' => false, 'message' => 'ID Fiscal manquant']);
    exit;
}

// 1. Define your default password
// 2. Apply your double MD5 hash
$hashedPassword =  md5(md5("Cio2025"));

try {
    $sql = "UPDATE agents SET password = :pw , needReset=1 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':pw' => $hashedPassword,
        ':id' => $idFiscal
    ]);

    // if ($stmt->rowCount() > 0) {
    echo json_encode([
        'success' => true,
        'message' => "Le mot de passe a été réinitialisé"
    ]);
    // } else {
    //     echo json_encode(['success' => false, 'message' => 'Agent non trouvé ou mot de passe déjà identique']);
    // }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
}
