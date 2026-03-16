<?php
header('Content-Type: application/json');
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

// Get the idFiscal from the URL (GET) or Body (POST)
$idFiscal = $_GET['id'] ?? null;

if (!$idFiscal) {
    echo json_encode(['success' => false, 'message' => 'ID Fiscal manquant']);
    exit;
}

try {
    // Soft delete: Update isDeleted to 1
    $stmt = $pdo->prepare("UPDATE agents SET isDeleted = 1 WHERE id = ?");
    $result = $stmt->execute([$idFiscal]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Agent non trouvé ou déjà supprimé']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
