<?php
header('Content-Type: application/json');

require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

$userUpdating = $_SESSION['id'];
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['id']) || empty($data['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID de l\'événement manquant.'
    ]);
    exit;
}

$eventId = $data['id'];

try {
    $queryAgent = $pdo->prepare("SELECT agentId FROM shifts WHERE id = :id LIMIT 1");
    $queryAgent->execute([':id' => $eventId]);
    $shift = $queryAgent->fetch(PDO::FETCH_ASSOC);

    $agent_id = $shift['agentId'] ?? null;
    // 5. Prepare and Execute Delete Query
    // Change 'shifts' to your actual table name
    $stmt = $pdo->prepare("UPDATE shifts SET isDeleted= 1 WHERE id = :id");
    $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            sendBulkNotification('Planning', 'Votre planning a été modifié. Merci de consulter vos nouveaux horaires.', [$agent_id], $_SESSION['id']);
            echo json_encode([
                'status' => 'success',
                'message' => 'Événement supprimé avec succès.'
            ]);
        } else {
            // ID existed in request but not in database
            echo json_encode([
                'status' => 'error',
                'message' => 'Événement non trouvé dans la base de données.'
            ]);
        }
    } else {
        throw new Exception("Erreur lors de l'exécution de la requête.");
    }
} catch (Exception $e) {
    // 6. Handle Errors
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur serveur : ' . $e->getMessage()
    ]);
}
