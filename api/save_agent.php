<?php
header('Content-Type: application/json');
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit;
}

// Default password for new agents: 123456
$defaultPassword = md5(md5("Cio2025"));

try {
    $sql = "INSERT INTO agents (idFiscal, idProx, nom, prenom, email, ste, campagne, role, password, isDeleted) 
            VALUES (:idFiscal, :idProx, :nom, :prenom, :email, :ste, :campagne, :role, :password, 0)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idFiscal' => $data['idFiscal'],
        ':idProx'   => $data['idProx'],
        ':nom'      => $data['nom'],
        ':prenom'   => $data['prenom'],
        ':email'    => $data['email'],
        ':ste'      => $data['ste'],
        ':campagne' => $data['campagne'],
        ':role'     => $data['role'],
        ':password' => $defaultPassword
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Check if email or idFiscal already exists
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'message' => 'Cet ID Fiscal ou Email existe déjà.']);
    } else {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
