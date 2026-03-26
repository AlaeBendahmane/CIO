<?php
header("Content-Type: application/json");
// $host = 'localhost';
// $db   = 'pointagedb';
// $user = 'root';
// $pass = '';

// $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
//     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
// ]);

require '../api/conf.php';

$action = $_GET['action'] ?? '';

$mois = isset($_GET['mois']) ? (int)$_GET['mois'] : (int)date('m');
$annee = isset($_GET['annee']) ? (int)$_GET['annee'] : (int)date('Y');

// var_dump($mois,$annee);die;

// $sess = 130;
require_once 'session_info.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
$sess = $idFiscal;
$_role = $role;

// function logActivity($pdo, $action, $idFiscal, $data, $m = null, $a = null, $by = null)
// {
//     $sql = "INSERT INTO activity_logs (action, idFiscal, details, mois, annee,updated_by) VALUES (?, ?, ?, ?, ?,?)";
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute([
//         $action,
//         $idFiscal,
//         json_encode($data),
//         $m,
//         $a,
//         $by
//     ]);
// }

// if ($action == 'fetch') {
//     $stmt = $pdo->prepare("SELECT a.*, p.totalJours, p.assiduite, p.avance, p.prime, p.cdp, p.remarque
//                            FROM agents a 
//                            LEFT JOIN user_performance p ON a.idFiscal = p.agent_id 
//                                 AND p.mois = :m AND p.annee = :a
//                            WHERE a.isDeleted = 0 and a.role!='A'
//                            ORDER BY a.idFiscal DESC");
//     $stmt->execute(['m' => $mois, 'a' => $annee]);
//     $agents = $stmt->fetchAll();

//     $pointages = [];
//     $stmtP = $pdo->prepare("SELECT agent_id_fiscal, jour_index, valeur FROM pointage WHERE mois = ? AND annee = ?");
//     $stmtP->execute([$mois, $annee]);

//     while ($p = $stmtP->fetch()) {
//         $pointages[$p['agent_id_fiscal']][$p['jour_index']] = $p['valeur'];
//     }

//     $result = [];
//     foreach ($agents as $agent) {
//         $days = array_fill(0, 30, '');
//         $id = $agent['idFiscal'];

//         if (isset($pointages[$id])) {
//             foreach ($pointages[$id] as $dayIndex => $val) {
//                 if ($dayIndex >= 1 && $dayIndex <= 31) {
//                     $days[$dayIndex - 1] = $val;
//                 }
//             }
//         }

//         $result[] = array_merge(
//             [$agent['ste'], $id, $agent['nom'], $agent['prenom'], $agent['campagne']],
//             $days,
//             [
//                 $agent['totalJours'] ?? 0,
//                 $agent['assiduite'] ?? '',
//                 $agent['avance'] ?? '',
//                 $agent['prime'] ?? '',
//                 $agent['cdp'] ?? '',
//                 $agent['remarque'] ?? ''
//             ]
//         );
//     }
//     echo json_encode($result);
// }


if ($action == 'fetch') {
    $sqqql = "SELECT a.*, p.totalJours, p.assiduite, p.avance, p.prime, p.cdp, p.remarque
                           FROM agents a 
                           LEFT JOIN user_performance p ON a.idFiscal = p.agent_id 
                                AND p.mois = :m AND p.annee = :a
                           WHERE a.isDeleted = 0 and a.role!='A'";

    if ($_role !== 'A') {
        $sqqql .= " AND a.idFiscal = :sess";
    }
    $sqqql .= " ORDER BY a.idFiscal DESC";

    $stmt = $pdo->prepare($sqqql);
    $stmt->bindValue(':m', $mois);
    $stmt->bindValue(':a', $annee);
    if ($_role !== 'A') {
        $stmt->bindValue(':sess', $sess);
    }
    $stmt->execute();
    $agents = $stmt->fetchAll();

    $pointages = [];
    $stmtP = $pdo->prepare("SELECT agent_id_fiscal, jour_index, valeur, commentaire FROM pointage WHERE mois = ? AND annee = ?");
    $stmtP->execute([$mois, $annee]);

    while ($p = $stmtP->fetch()) {
        $id = $p['agent_id_fiscal'];
        $idx = $p['jour_index'];
        $pointages[$id][$idx] = [
            'v' => $p['valeur'],
            'c' => $p['commentaire']
        ];
    }

    $result = [];
    $numdays = getDaysInMonth($annee, $mois);
    foreach ($agents as $agent) {
        $days = array_fill(0, $numdays, ''); // Valeurs
        $comments = array_fill(0, $numdays, null); // Commentaires
        $id = $agent['idFiscal'];

        if (isset($pointages[$id])) {
            foreach ($pointages[$id] as $dayIndex => $data) {
                if ($dayIndex >= 1 && $dayIndex <= 31) {
                    $days[$dayIndex - 1] = $data['v'];
                    $comments[$dayIndex - 1] = $data['c'];
                }
            }
        }
        $rowData = array_merge(
            [$agent['ste'], $id, $agent['nom'], $agent['prenom'], $agent['campagne']],
            $days,
            [
                $agent['totalJours'] ?? 0,
                $agent['assiduite'] ?? '',
                $agent['avance'] ?? '',
                $agent['prime'] ?? '',
                $agent['cdp'] ?? '',
                $agent['remarque'] ?? ''
            ]
        );

        $result[] = [
            'data' => $rowData,
            'comments' => $comments
        ];
    }
    echo json_encode($result);
}

if ($action == 'add_agent') {
    isAdminQuery();
    $d = json_decode(file_get_contents('php://input'), true);

    $idFiscal = $d['idFiscal'];

    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM user_performance WHERE agent_id = ? AND mois = ? AND annee = ?");
    $checkStmt->execute([$idFiscal, $mois, $annee]);
    $exists = $checkStmt->fetchColumn() > 0;

    $m = $mois; // isset($_GET['mois']) ? (int)$_GET['mois'] : (int)date('m');
    $a = $annee; //isset($_GET['annee']) ? (int)$_GET['annee'] : (int)date('Y');

    $hashedPassword = getParam($pdo, 'DefPassword');

    $sqlAgent = "INSERT INTO agents (ste, idFiscal, nom, prenom, campagne, role, isDeleted,password) 
                 VALUES (?, ?, ?, ?, ?, 'U', 0,?)
                 ON DUPLICATE KEY UPDATE 
                 ste=VALUES(ste), nom=VALUES(nom), prenom=VALUES(prenom), campagne=VALUES(campagne), isDeleted=0";

    $pdo->prepare($sqlAgent)->execute([$d['ste'] ?? "", $d['idFiscal'], mb_strtoupper($d['nom']) ?? "", $d['prenom'] ?? "", $d['campagne'] ?? "", $hashedPassword]);

    $sqlPerf = "INSERT INTO user_performance (agent_id, mois, annee, totalJours, assiduite, avance, prime, cdp, remarque) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                totalJours=VALUES(totalJours), assiduite=VALUES(assiduite), avance=VALUES(avance), 
                prime=VALUES(prime), cdp=VALUES(cdp), remarque=VALUES(remarque)";

    $pdo->prepare($sqlPerf)->execute([
        $d['idFiscal'],
        $m,
        $a,
        $d['totalJours'] ?? 0,
        $d['assiduite'] ?? '',
        $d['avance'] ?? '',
        $d['prime'] ?? '',
        $d['cdp'] ?? '',
        $d['remarque'] ?? ''

    ]);
    $updatedRow = $d['updatedRow'] ?? '';
    if (!$exists) {
        // This is the first time this agent appears in this month/year
        // logActivity($pdo, 'add_agent', $idFiscal, $d, $mois, $annee, $sess);
        logActivity($pdo, 'update_agent_' . $updatedRow, $idFiscal, $d, $mois, $annee, $sess);
    } else {
        if ($updatedRow == "totalJours") {
            //walo
        } else {
            logActivity($pdo, 'update_agent_' . $updatedRow, $idFiscal, $d, $mois, $annee, $sess);
        }
    }
    echo json_encode(['status' => 'ok']);
}

if ($action == 'save_pointage') {
    isAdminQuery();
    $d = json_decode(file_get_contents('php://input'), true);
    $_m = $mois; // isset($_GET['mois']) ? (int)$_GET['mois'] : (int)date('m');
    $_a = $annee; //isset($_GET['annee']) ? (int)$_GET['annee'] : (int)date('Y');
    if ($d) {
        $idFiscal = $d['idFiscal'];
        $day      = $d['day'];
        $valeur   = str_replace(',', '.', $d['valeur']);
        $m        = $_m;
        $a        = $_a;

        $sql = "INSERT INTO pointage (agent_id_fiscal, jour_index, mois, annee, valeur) 
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE valeur = VALUES(valeur)";

        $pdo->prepare($sql)->execute([$idFiscal, $day, $m, $a, $valeur]);
        logActivity($pdo, 'save_pointage', $idFiscal, $d, $mois, $annee, $sess);
        echo json_encode(['status' => 'ok']);
    }
}

if ($action == 'delete_agent') {
    isAdminQuery();
    $id = $_GET['idFiscal'];
    $sql = "UPDATE agents SET isDeleted = 1 WHERE idFiscal = ?";
    $pdo->prepare($sql)->execute([$id]);
    echo json_encode(['status' => 'ok']);
}

if ($action == 'fetch_history') {
    isAdminQuery();
    $idFiscal = $_GET['idFiscal'] ?? null;
    $params = [];

    $query = "SELECT 
    l.id as id, 
    l.action, 
    l.idFiscal, 
    l.details, 
    l.mois, 
    l.annee, 
    l.updated_by,
    DATE(l.created_at) as log_date, 
    TIME(l.created_at) as log_time,
    l.created_at as created_at,
    author.nom as author_nom, 
    author.prenom as author_prenom,
    target.nom as target_nom, 
    target.prenom as target_prenom
FROM activity_logs l
LEFT JOIN agents author ON l.updated_by = author.idFiscal
LEFT JOIN agents target ON l.idFiscal = target.idFiscal
WHERE l.action NOT IN (
    'update_agent_Prénom', 
    'update_agent_Nom', 
    'update_agent_Campagne', 
    'update_agent_STE', 
    'update_agent_totalJours', 
    'update_agent_ID Fiscal')";
    //old one v2 remove min bcs it block others to show and remove # in groupby
    //     "SELECT 
    //     MIN(l.id) as id, 
    //     l.action, 
    //     l.idFiscal, 
    //     l.details, 
    //     l.mois, 
    //     l.annee, 
    //     l.updated_by,
    //     DATE(MIN(l.created_at)) as log_date, 
    //     TIME(MIN(l.created_at)) as log_time,
    //     MIN(l.created_at) as created_at,
    //     author.nom as author_nom, 
    //     author.prenom as author_prenom,
    //     target.nom as target_nom, 
    //     target.prenom as target_prenom
    // FROM activity_logs l
    // LEFT JOIN agents author ON l.updated_by = author.idFiscal
    // LEFT JOIN agents target ON l.idFiscal = target.idFiscal
    // WHERE l.action NOT IN (
    //     'update_agent_Prénom', 
    //     'update_agent_Nom', 
    //     'update_agent_Campagne', 
    //     'update_agent_STE', 
    //     'update_agent_totalJours', 
    //     'update_agent_ID Fiscal')"
    //old one v1
    // "SELECT 
    //     MIN(l.id) as id, 
    //     l.action, 
    //     l.idFiscal, 
    //     l.details, 
    //     l.mois, 
    //     l.annee, 
    //     l.updated_by,
    //     DATE(MIN(l.created_at)) as log_date, 
    //     TIME(MIN(l.created_at)) as log_time,
    //     MIN(l.created_at) as created_at,
    //     author.nom as author_nom, 
    //     author.prenom as author_prenom,
    //     target.nom as target_nom, 
    //     target.prenom as target_prenom
    // FROM activity_logs l
    // LEFT JOIN agents author ON l.updated_by = author.idFiscal
    // LEFT JOIN agents target ON l.idFiscal = target.idFiscal";

    if ($idFiscal) {
        $query .= " WHERE l.idFiscal = ? ";
        $params[] = $idFiscal;
    }

    $query .= " #GROUP BY 
                #    l.action, 
                #    l.idFiscal, 
                #    l.details, 
                #    l.mois, 
                #    l.annee, 
                #    l.updated_by,
                #    author.nom,
                #    author.prenom,
                #    target.nom,
                #    target.prenom
                ORDER BY created_at DESC 
                LIMIT 90";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $logs = $stmt->fetchAll();

    echo json_encode($logs);
}

if ($action == 'save_comment') {
    isAdminQuery();
    $d = json_decode(file_get_contents('php://input'), true);
    $mois = $_GET['mois'] ?? null;
    $annee = $_GET['annee'] ?? null;
    $sql = "INSERT INTO pointage (agent_id_fiscal, jour_index, mois, annee, commentaire) 
            VALUES (?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE commentaire = VALUES(commentaire)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $d['idFiscal'],
        $d['day'],
        $mois,
        $annee,
        $d['comment']
    ]);

    logActivity($pdo, 'update_commentaire', $d['idFiscal'], ['comment' => $d['comment'], 'day' => $d['day']], $mois, $annee, $sess);

    echo json_encode(['status' => 'success']);
    exit;
}

if ($action == 'save_bulk_pointage') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $updates = $data['updates'] ?? [];
    $mois = $_GET['mois'] ?? null;
    $annee = $_GET['annee'] ?? null;

    $days = array_column($updates, 'jour_index');
    $fromDay = min($days);
    $toDay   = max($days);

    if (empty($updates) || !$mois || !$annee) {
        echo json_encode(["status" => "error", "message" => "Missing data"]);
        exit;
    }

    $agentStats = [];

    try {
        $pdo->beginTransaction();
        $sql = "INSERT INTO pointage (agent_id_fiscal, jour_index, valeur, mois, annee) 
                VALUES (:agent_id_fiscal, :jour_index, :valeur, :mois, :annee)
                ON DUPLICATE KEY UPDATE valeur = VALUES(valeur)";

        $stmt = $pdo->prepare($sql);

        foreach ($updates as $row) {
            $id = $row['agent_id_fiscal'];
            $day = $row['jour_index'];
            $stmt->execute([
                ':agent_id_fiscal' => $row['agent_id_fiscal'],
                ':jour_index'      => $row['jour_index'],
                ':valeur'   => $row['valeur'],
                ':mois'     => $mois,
                ':annee'    => $annee
            ]);

            if (!isset($agentStats[$id])) {
                $agentStats[$id] = ['days' => []];
            }
            $agentStats[$id]['days'][] = $day;
        }

        $pdo->commit();
        foreach ($agentStats as $agentId => $data) {
            $fromDay = min($data['days']);
            $toDay   = max($data['days']);
            $count   = count($data['days']);

            logActivity(
                $pdo,
                'save_bulk_pointage',
                $agentId,
                ['From' => $fromDay, 'To' => $toDay, 'Count' => $count],
                $mois,
                $annee,
                $sess
            );
        }
        echo json_encode(["status" => "ok", "count" => count($updates)]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
