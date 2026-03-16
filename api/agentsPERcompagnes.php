<?php
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();
// 1. Database Connection (Assumed already exists as $pdo)
// Replace 'agents' with your actual table name if different
$query = $pdo->query("SELECT c.nomCompagne AS campagne, COUNT(a.id) AS total FROM compagne c LEFT JOIN agents a ON c.abreviation = a.campagne AND a.isDeleted = 0 GROUP BY c.nomCompagne;"); //SELECT campagne, COUNT(*) as total FROM agents WHERE isDeleted = 0 GROUP BY campagne
$results = $query->fetchAll(PDO::FETCH_ASSOC);

// 2. Prepare arrays for ApexCharts
$campagneNames = array_column($results, 'campagne');
$agentCounts = array_map('intval', array_column($results, 'total')); // Ensure they are numbers
