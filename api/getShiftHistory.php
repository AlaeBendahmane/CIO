<?php
header('Content-Type: application/json');

require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

$shiftId = $_GET['shift_id'] ?? '';

if (empty($shiftId)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID du shift manquant.'
    ]);
    exit;
}

try {
    // We select log data AND join the users table to grab the creator's full name
    $sql = "SELECT l.*, u.nom, u.prenom 
            FROM shifts_logs l
            LEFT JOIN agents u ON l.changed_by = u.id 
            WHERE l.shift_id = :shift_id 
            ORDER BY l.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':shift_id' => $shiftId]);

    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $history
    ]);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur Database: ' . $e->getMessage()
    ]);
}
