<?php
header('Content-Type: application/json');
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

// 1. Get JSON Data
$data = json_decode(file_get_contents('php://input'), true);

$title      = $data['title'] ?? '';
$start_time = $data['start'] ?? '';
$end_time   = $data['end'] ?? '';
$agent_id   = $data['agent_id'] ?? '';

// 2. Validation
if (empty($title) || empty($start_time) || empty($end_time) || empty($agent_id)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs obligatoires']);
    exit;
}

try {

    $sql = "INSERT INTO shifts (agentId, shift_type, start_time, end_time) 
            VALUES (:agentId, :shift_type, :start_time, :end_time)";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':agentId'    => $agent_id,
        ':shift_type' => $title,
        ':start_time' => $start_time,
        ':end_time'   => $end_time,
    ]);

    if ($result) {
        $newShiftId = $pdo->lastInsertId();

        // Update the $data array with the real ID before logging
        $data['id'] = $newShiftId;

        // Log the creation
        logShiftChange($pdo, $newShiftId, 'CREATE', null, $data);

        // Notify the agent
        sendBulkNotification(
            'Planning',
            'Un nouveau shift a été ajouté. Veuillez vérifier votre planning.',
            [$agent_id],
            $_SESSION['id']
        );

        echo json_encode(['success' => true, 'message' => 'Shift ajouté avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'insertion']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur Database: ' . $e->getMessage()]);
}
