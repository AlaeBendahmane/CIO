<?php
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();
header('Content-Type: application/json');
try {
    $sql = "SELECT c.nomCompagne AS campagne, COUNT(a.id) AS total 
            FROM compagne c 
            LEFT JOIN agents a ON c.abreviation = a.campagne COLLATE utf8mb4_unicode_ci 
            AND a.isDeleted = 0 
            GROUP BY c.nomCompagne";

    $query = $pdo->query($sql);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'labels' => array_column($results, 'campagne'),
        'series' => array_map('intval', array_column($results, 'total'))
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
