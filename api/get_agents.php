<?php
header('Content-Type: application/json');

require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();
$from = $_GET['from'] ?? null;
try {
    if ($from == "notifications") {
        $stmt = $pdo->prepare("SELECT id, idFiscal ,idProx, nom, prenom FROM agents WHERE isDeleted = 0");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = array_map(function ($user) {
            $user['nom'] = strtoupper($user['nom']);
            $user['prenom'] = strtoupper($user['prenom']);
            return $user;
        }, $users);

        echo json_encode([
            'status' => 'success',
            'data' => $users
        ]);
    } else {
        $stmt = $pdo->prepare("SELECT id, idFiscal, idProx, nom, prenom, email, ste, campagne ,role FROM agents WHERE isDeleted = 0");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = array_map(function ($user) {
            $user['nom'] = strtoupper($user['nom']);
            $user['prenom'] = strtoupper($user['prenom']);
            $user['ste'] = strtoupper($user['ste']);
            $user['campagne'] = strtoupper($user['campagne']);
            $_role = 'U';
            if ($user['role'] == 'U') {
                $_role = 'Agent';
            } else if ($user['role'] == 'C') {
                $_role = 'Coach';
            } else if ($user['role'] == 'A') {
                $_role = 'Administrateur';
            } else if ($user['role'] == 'M') {
                $_role = 'Mex';
            } else {
                $_role = 'Agent';
            }
            $user['role'] = $_role;
            return $user;
        }, $users);

        echo json_encode([
            'status' => 'success',
            'data' => $users
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error.',
        'debug' => $e->getMessage() // remove in production
    ]);
}
