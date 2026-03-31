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
    $sql = "SELECT a.nom,
                a.prenom,
                COALESCE(SUM(CAST(p.valeur AS UNSIGNED)), 0) as total_h
            FROM agents a
                LEFT JOIN pointage p ON a.idFiscal = p.agent_id_fiscal
                AND p.mois = ?
                AND p.annee = ?
            WHERE a.isDeleted = 0
            GROUP BY a.idFiscal,
                a.nom,
                a.prenom
            ORDER BY total_h DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$m, $a]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
