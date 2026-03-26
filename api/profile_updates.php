<?php
session_start();
header('Content-Type: application/json');


require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();


if ($_GET['action'] === 'update_photo') {
    // Get the JSON data from the request body
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (isset($data['image']) && isset($_SESSION['id'])) {
        $base64 = $data['image'];
        $userId = $_SESSION['id'];

        try {
            // Update the user's profile_pic column
            $stmt = $pdo->prepare("UPDATE agents SET profilePic = :img WHERE id = :id");
            $stmt->execute([
                ':img' => $base64,
                ':id'  => $userId
            ]);
            $_SESSION['profilePic'] = $base64;
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid data or session expired']);
    }
    exit;
}
if (isset($_GET['action']) && $_GET['action'] === 'change_password') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Use idFiscal as the unique identifier from your session
    $idFiscal = $_SESSION['idFiscal'] ?? null;
    $oldPw = $data['oldPw'] ?? '';
    $newPw = $data['newPw'] ?? '';

    if ($data['from'] == "required") {

        $newHashed = md5(md5($newPw));

        // 4. Update the database
        $update = $pdo->prepare("UPDATE agents SET password = ? , needReset=0 WHERE idFiscal = ?");
        $update->execute([$newHashed, $idFiscal]);
        $_SESSION['needReset'] = 0;
        echo json_encode(['success' => true, 'message' => 'Mot de passe mis à jour']);
    } else  if ($data['from'] == "profile") {

        if (!$idFiscal || empty($oldPw) || empty($newPw)) {
            echo json_encode(['success' => false, 'message' => 'Données incomplètes ou session expirée']);
            exit;
        }

        if ($oldPw == $newPw) {
            echo json_encode(['success' => false, 'message' => 'Le nouveau mot de passe ne doit pas être similaire à l’ancien.']);
            exit;
        }

        // 1. Hash the OLD password provided to compare with DB
        $oldHashed = md5(md5($oldPw));

        // 2. Verify if the old password matches what is in the DB
        $stmt = $pdo->prepare("SELECT password FROM agents WHERE idFiscal = ? AND isDeleted = 0 LIMIT 1");
        $stmt->execute([$idFiscal]);
        $user = $stmt->fetch();

        if ($user && $oldHashed === $user['password']) {
            // 3. Hash the NEW password using your double MD5 logic
            $newHashed = md5(md5($newPw));

            // 4. Update the database
            $update = $pdo->prepare("UPDATE agents SET password = ? , needReset=0 WHERE idFiscal = ?");
            $update->execute([$newHashed, $idFiscal]);

            echo json_encode(['success' => true, 'message' => 'Mot de passe mis à jour']);
        } else {
            echo json_encode(['success' => false, 'message' => 'L\'ancien mot de passe est incorrect']);
        }
    }
    exit;
}
