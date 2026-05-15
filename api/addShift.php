<?php
header('Content-Type: application/json');
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();


// 2. Get JSON Data
$data = json_decode(file_get_contents('php://input'), true);

$title      = $data['title'] ?? '';
$start_time = $data['start'] ?? ''; // e.g., "08:00"
$end_time   = $data['end'] ?? '';   // e.g., "17:00"
$agent_id   = $data['agent_id'] ?? '';


// 3. Validation
if (empty($title) || empty($start_time) || empty($end_time) || empty($agent_id)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires']);
    exit;
}

try {
    // 5. Perform the INSERT
    $sql = "INSERT INTO shifts (agentId, shift_type, start_time, end_time) 
            VALUES (:agentId, :shift_type, :start_time, :end_time)";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':agentId'   => $agent_id,
        ':shift_type'      => $title,
        ':start_time' => $start_time,
        ':end_time'      => $end_time,
    ]);

    if ($result) {
        sendBulkNotification('Planning', 'Votre planning a été modifié. Merci de consulter vos nouveaux horaires.', [$agent_id], $_SESSION['id']);
        echo json_encode(['success' => true, 'message' => 'Shift ajouté avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'insertion']);
    }
} catch (PDOException $e) {
    // Return a clean error for your SweetAlert2 to display
    echo json_encode(['success' => false, 'message' => 'Erreur Database: ' . $e->getMessage()]);
}
