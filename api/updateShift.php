<?php
header('Content-Type: application/json');
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

$data = json_decode(file_get_contents('php://input'), true);

$id         = $data['id'] ?? '';
$title      = $data['title'] ?? '';
$start_time = $data['start'] ?? '';
$end_time   = $data['end'] ?? '';

// 1. Validation
if (empty($id) || empty($title) || empty($start_time) || empty($end_time)) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

try {
    // 2. Fetch current state and agent ID before update
    $stmt = $pdo->prepare("SELECT * FROM shifts WHERE id = ?");
    $stmt->execute([$id]);
    $oldShift = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$oldShift) {
        echo json_encode(['success' => false, 'message' => 'Shift introuvable']);
        exit;
    }

    // Use the database agentId if it's not provided in the request
    $target_agent_id = $data['agent_id'] ?? $oldShift['agentId'];

    // 3. Perform the UPDATE
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
        // Get current system time for the update stamp
        $currentTimestamp = date('Y-m-d H:i:s');

        // 4. Normalize the new data array to perfectly match database column keys
        $normalizedNewData = [
            'id'         => (int)$id,
            'agentId'    => (int)$target_agent_id,
            'end_time'   => str_replace('T', ' ', $end_time),
            'isDeleted'  => (int)$oldShift['isDeleted'],
            'shift_type' => $title,
            'start_time' => str_replace('T', ' ', $start_time),
            'created_at' => $oldShift['created_at'], // Keep the exact same creation time
            'updated_at' => $currentTimestamp        // Set a fresh updated_at timestamp
        ];

        // Pass the normalized array instead of the raw frontend $data
        logShiftChange($pdo, $id, 'UPDATE', $oldShift, $normalizedNewData);

        // 5. Notify the correct agent
        sendBulkNotification(
            'Planning',
            'Votre planning a été modifié. Veuillez vérifier vos nouveaux horaires.',
            [$target_agent_id],
            $_SESSION['id']
        );

        echo json_encode(['success' => true, 'message' => 'Shift mis à jour avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucune modification enregistrée']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur Database: ' . $e->getMessage()]);
}
