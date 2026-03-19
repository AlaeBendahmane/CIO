<?php
header('Content-Type: application/json');

// Connexion à la base de données
// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=pointagedb;charset=utf8', 'root', '');
// } catch (Exception $e) {
//     die(json_encode(['success' => false, 'message' => 'Erreur de connexion DB']));
// }
require_once 'conf.php';

// Récupération des données JSON envoyées par le JS
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs']);
    exit;
}

$hashedPassword = md5(md5(string: $password));
// var_dump($hashedPassword);die;

// 1. Chercher l'agent (on vérifie aussi qu'il n'est pas supprimé)
$stmt = $pdo->prepare("SELECT id,idFiscal,idProx,nom,prenom,email,password,ste,role,token,createdAt,isDeleted,needReset, campagne,profilePic FROM agents WHERE email = ? AND isDeleted = 0 LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // 2. Vérifier le mot de passe (Haché ou clair selon votre base actuelle)
    // Note : Utilisez password_verify() si vous utilisez password_hash() en PHP
    $user = array_map('trim', $user);
    if ($hashedPassword === $user['password']) {
        $token = bin2hex(random_bytes(8));
        $reformatedToken = str_replace(' ', '', $token);
        $sql = "UPDATE agents 
            SET token = :token 
            WHERE idFiscal = :idFiscal";
        $stmt = $pdo->prepare($sql);

        $result = $stmt->execute([
            ':idFiscal' => $user['idFiscal'],
            ':token' => $reformatedToken
        ]);

        session_start();
        // 3. Créer la session
        $_SESSION['idFiscal'] = $user['idFiscal'];
        $_SESSION['idProx'] = $user['idProx'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['ste'] = $user['ste'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['token'] = $reformatedToken;
        $_SESSION['needReset'] = $user['needReset'];
        $_SESSION['campagne'] = $user['campagne'];
        $_SESSION['profilePic'] = $user['profilePic'];

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email inconnu ou compte désactivé']);
}
