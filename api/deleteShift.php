<?php
header('Content-Type: application/json');

require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (empty($data['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID de l\'événement manquant.'
    ]);
    exit;
}

$eventId = $data['id'];

try {
    // 1. Fetch the shift data ONCE. 
    // This gives us both the 'old_data' for the log and the 'agentId' for the notification.
    $stmt = $pdo->prepare("SELECT * FROM shifts WHERE id = ? LIMIT 1");
    $stmt->execute([$eventId]);
    $currentShift = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$currentShift) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Événement non trouvé dans la base de données.'
        ]);
        exit;
    }

    $agent_id = $currentShift['agentId'];

    // 2. Perform Soft Delete
    $stmt = $pdo->prepare("UPDATE shifts SET isDeleted = 1 WHERE id = :id");
    $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // 3. Log the change using the data we fetched in step 1
        logShiftChange($pdo, $eventId, 'DELETE', $currentShift, ['isDeleted' => 1]);

        // 4. Notify the agent
        sendBulkNotification(
            'Planning',
            'Votre planning a été modifié. Merci de consulter vos nouveaux horaires.',
            [$agent_id],
            $_SESSION['id']
        );

        echo json_encode([
            'status' => 'success',
            'message' => 'Événement supprimé avec succès.'
        ]);
    } else {
        throw new Exception("Erreur lors de l'exécution de la requête.");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur serveur : ' . $e->getMessage()
    ]);
}
