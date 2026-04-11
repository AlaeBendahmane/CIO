<?php
require_once 'helpers.php';
require '../api/conf.php';
session_start();
isAuthQuery();
isAdminQuery();
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action == 'get_notifs') {
    $myIdFiscal = $_SESSION['id'] ?? '';

    //old
    // $sql = "SELECT id, title, content FROM notifications 
    //         WHERE isSeen = 0 AND (toUser = ? OR toUser = 'ALL') AND isSent = 0
    //         ORDER BY id ASC LIMIT 5";


    $sql = "SELECT 
                n.id as id, 
                n.title as title, 
                n.content as content, 
                CONCAT(u.nom, ' ', u.prenom) AS sender,
                u.profilePic as senderPic
            FROM notifications n
            LEFT JOIN agents u ON n.fromAdmin = u.id
            WHERE n.isSeen = 0 
            AND (n.toUser = ? OR n.toUser = 'ALL') 
            AND n.isSent = 0
            ORDER BY n.id ASC 
            LIMIT 5";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$myIdFiscal]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($action == 'mark_sent') {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("UPDATE notifications SET isSent = 1 WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['status' => true, 'message' => 'delivered']);
    exit;
}

if ($action == 'mark_read') {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("UPDATE notifications SET isSeen = 1 WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['status' => true, 'message' => 'read']);
    exit;
}

if ($action == 'send_notif') {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    if (!$data || !isset($data['title'], $data['message'], $data['agents'])) {
        echo json_encode(["status" => "error", "message" => "Données incomplètes."]);
        exit;
    }

    $title = $data['title'];
    $content = $data['message'];
    $agents = $data['agents'];
    $fromAdmin = $_SESSION['id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO notifications (title, content, fromAdmin, toUser, isSent, isSeen) 
                           VALUES (?, ?, ?, ?, 0, 0)");

        $successCount = 0;

        foreach ($agents as $agentId) {
            if ($stmt->execute([$title, $content, $fromAdmin, $agentId])) {
                $successCount++;
            }
        }

        echo json_encode([
            "status" => "success",
            "message" => "$successCount notifications envoyées avec succès."
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Erreur DB: " . $e->getMessage()
        ]);
    }
}
