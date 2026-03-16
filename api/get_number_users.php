<?php
header('Content-Type: application/json');

require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();


try {
    // 1. Fetch the statistics (Totals)
    $statsStmt = $pdo->query("SELECT 
        COUNT(*) AS total_active,
        SUM(CASE WHEN role = 'U' THEN 1 ELSE 0 END) AS count_u,
        SUM(CASE WHEN role = 'A' THEN 1 ELSE 0 END) AS count_a,
        SUM(CASE WHEN role = 'C' THEN 1 ELSE 0 END) AS count_c,
         SUM(CASE WHEN role = 'M' THEN 1 ELSE 0 END) AS count_m
    FROM agents 
    WHERE isDeleted = 0");
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);


    echo json_encode([
        'status' => 'success',
        'message' => [
            'total' => (int)$stats['total_active'],
            'total_u' => (int)$stats['count_u'],
            'total_a' => (int)$stats['count_a'],
            'total_c' => (int)$stats['count_c'],
            'total_m' => (int)$stats['count_m'],
        ],
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error.',
        'debug' => $e->getMessage()
    ]);
}
