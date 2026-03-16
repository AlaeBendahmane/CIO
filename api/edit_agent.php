<?php
header('Content-Type: application/json');
require_once 'conf.php'; // Database connection
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

// 1. Get the JSON payload
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['updatedAgent'])) {
    echo json_encode(['success' => false, 'message' => 'Données incomplètes ou ID manquant.']);
    exit;
}

try {
    // 2. Prepare the Update Query
    // Note: We do NOT update idFiscal because it is the identifier (Primary Key)
    $sql = "UPDATE agents 
            SET nom = :nom, 
                prenom = :prenom, 
                email = :email, 
                ste = :ste, 
                campagne = :campagne, 
                idProx=:idProx,
                idFiscal=:idFiscal,
                role = :role 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $result = $stmt->execute([
        ':nom'      => $data['nom'],
        ':prenom'   => $data['prenom'],
        ':email'    => $data['email'],
        ':ste'      => $data['ste'],
        ':campagne' => $data['campagne'],
        ':role'     => $data['role'],
        ':idProx'     => $data['idProx'],
        ':idFiscal'     => $data['idFiscal'],
        ':id' => $data['updatedAgent']
    ]);

    // 3. Check if anything actually changed
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Agent mis à jour avec succès.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucune modification effectuée.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur base de données: ' . $e->getMessage()]);
}
