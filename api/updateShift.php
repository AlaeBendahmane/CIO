<?php
header('Content-Type: application/json');
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

// 1. Get JSON Data
$data = json_decode(file_get_contents('php://input'), true);

$id         = $data['id'] ?? '';
$title      = $data['title'] ?? '';
$start_time = $data['start'] ?? ''; // Format: "YYYY-MM-DDTHH:mm:ss"
$end_time   = $data['end'] ?? '';   // Format: "YYYY-MM-DDTHH:mm:ss"
$agent_id   = $data['agent_id'] ?? '';

// 2. Validation
// Note: We check for $id because we can't update without a reference
if (empty($id) || empty($title) || empty($start_time) || empty($end_time)) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes pour la mise à jour']);
    exit;
}

try {
    // 3. Perform the UPDATE
    // We update the specific shift matched by its ID
    $sql = "UPDATE shifts 
            SET shift_type = :shift_type, 
                start_time = :start_time, 
                end_time   = :end_time 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':shift_type' => $title,
        ':start_time' => $start_time,
        ':end_time'   => $end_time,
        ':id'         => $id,
    ]);

    if ($result) {
        sendBulkNotification('Planning', 'Votre planning a été modifié. Merci de consulter vos nouveaux horaires.', [$agent_id], $_SESSION['id']);
        echo json_encode(['success' => true, 'message' => 'Shift mis à jour avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucun changement effectué ou erreur de mise à jour']);
    }
} catch (PDOException $e) {
    // Return a clean error for your SweetAlert2 to display
    echo json_encode(['success' => false, 'message' => 'Erreur Database: ' . $e->getMessage()]);
}