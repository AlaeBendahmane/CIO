<?php
header('Content-Type: application/json');

require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();

try {
    $stmt = $pdo->prepare("SELECT a.nom, a.prenom, a.email, a.campagne, s.nomSte as ste, a.profilePic, a.role FROM agents a LEFT JOIN ste s ON a.ste = s.abreviation WHERE a.isDeleted = 0 AND a.role != 'U';");//SELECT  nom, prenom, email,campagne,ste,profilePic, role FROM agents WHERE isDeleted = 0 And role !='U'
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $users = array_map(function ($user) {
        $user['nom'] = strtoupper($user['nom']);
        $user['prenom'] = strtoupper($user['prenom']);
        $user['ste'] = strtoupper($user['ste']);
        $user['campagne'] = strtoupper($user['campagne']);
        $_role = 'null';
         if ($user['role'] == 'C') {
            $_role = 'Coach';
        } else if ($user['role'] == 'A') {
            $_role = 'Administrateur';
        } else if ($user['role'] == 'M') {
            $_role = 'Mex';
        } else {
            $_role = 'null';
        }
        $user['role'] = $_role;
        return $user;
    }, $users);

    echo json_encode([
        'status' => 'success',
        'data' => $users
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error.',
        // 'debug' => $e->getMessage() // remove in production
    ]);
}
