<?php
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();
header('Content-Type: application/json');

$m = $_GET['mois'] ?? date('n');
$a = $_GET['annee'] ?? date('Y');

try {
    $sql = "SELECT a.nom, a.prenom, SUM(CAST(p.valeur AS UNSIGNED)) as total_h 
                FROM pointage p
                JOIN agents a ON p.agent_id_fiscal  = a.idFiscal
                WHERE p.mois = ? AND p.annee = ? AND a.isDeleted = 0
                GROUP BY p.agent_id_fiscal 
                ORDER BY total_h DESC ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$m, $a]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
