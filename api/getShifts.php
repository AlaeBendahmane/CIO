<?php
header('Content-Type: application/json');

require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();

$sessionAgentId = ''; //isset($_GET['agent_id']) ? $_GET['agent_id'] : $_SESSION['id'];

if (isset($_GET['agent_id']) && $_GET['agent_id'] !== '') {
    isAdminQuery();
    $sessionAgentId = $_GET['agent_id'] ?? $_SESSION['id'];
} else {
    $sessionAgentId = $_SESSION['id'];
}

try {
    $stmtColor = $pdo->prepare("SELECT valueP FROM parametres WHERE keyP = 'shiftsColors'");
    $stmtColor->execute();
    $colorData = $stmtColor->fetch();
    $colorMap = json_decode($colorData['valueP'], true);

    $stmt = $pdo->prepare("
        SELECT id, shift_type, start_time AS start, end_time AS end 
        FROM shifts 
        WHERE agentId = :agentId and isDeleted=0
    ");
    $stmt->execute(['agentId' => $sessionAgentId]);
    $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $events = array_map(function ($shift) use ($colorMap) {
        $type = $shift['shift_type'];

        $start_time = date("H:i", strtotime($shift['start']));
        $end_time   = date("H:i", strtotime($shift['end']));

        $config = $colorMap[$type] ?? null;
        $bg = $config['color'] ?? '#3788d8';
        $text = $config['textColor'] ?? '#FFFFFF';

        return [
            'id' => $shift['id'],
            "title" => strtoupper(str_replace('_', ' ', $type))  . " " . $start_time . " " . $end_time,
            'start' => $shift['start'],
            'end' => $shift['end'],
            'backgroundColor' => $bg,
            'borderColor' => $bg,
            'textColor' => $text
        ];
    }, $shifts);
    echo json_encode($events);
} catch (\PDOException $e) {
    echo json_encode(['error' => 'Database error occurred']);
}
