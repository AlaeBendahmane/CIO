<?php
// Disable all error reporting to the browser
error_reporting(0);
ini_set('display_errors', 0);

// Log the errors to a file instead so YOU can still see them
ini_set('log_errors', 1);
ini_set('error_log', 'C:/wamp64/logs/php_error_CIO.log');

function isAuthPath()
{
    if (/*session_status() === PHP_SESSION_NONE ||*/empty($_SESSION)) {
        header("Location: ../dist/");
        exit();
    }
}

function isAdminPath()
{
    // isAuthPath();//gher kat3awd
    if ($_SESSION['role'] !== 'A') {
        header('Location: ../dist/404.html');
        exit();
    }
}


function isAuthQuery()
{
    if (/*session_status() === PHP_SESSION_NONE || */empty($_SESSION)) {
        echo json_encode(['success' => false, 'message' => 'Session expirée']);
        exit;
    }
}

function isAdminQuery()
{
    if (/*session_status() === PHP_SESSION_NONE || */$_SESSION['role'] !== 'A') {
        echo json_encode(['success' => false, 'message' => 'Vous n\'avez pas les autorisations nécessaires pour effectuer cette action.']);
        exit();
    }
}

function isAlreadyAuth()
{
    if (/*session_status() === PHP_SESSION_NONE ||*/!empty($_SESSION)) {
        header("Location: ../dist/Dashboard");
        exit();
    }
}

// //tocheck
// function encodeImageToBase64($path)
// {
//     if (!file_exists($path)) return "";

//     $data = file_get_contents($path);
//     $type = mime_content_type($path);

//     // Returns: data:image/jpeg;base64,/9j/4AAQSkZJRg...
//     return 'data:' . $type . ';base64,' . base64_encode($data);
// }

function decodeBase64ToImage($base64String)
{
    if (empty($base64String)) {
        return "assets/img/avatar5.png";
    }
    if (strpos($base64String, 'data:image') === 0) {
        return $base64String;
    }

    $firstChar = substr($base64String, 0, 1);

    switch ($firstChar) {
        case 'i':
            $mime = 'image/png';
            break;
        case 'R':
            $mime = 'image/gif';
            break;
        case 'U':
            $mime = 'image/webp';
            break;
        default:
            $mime = 'image/jpeg'; // Default fallback
            break;
    }

    return 'data:' . $mime . ';base64,' . $base64String;
}

function getDaysInMonth($year, $month)
{
    // 't' returns the number of days in the given month (28 through 31)
    // mktime(hour, minute, second, month, day, year)
    return (int)date('t', mktime(0, 0, 0, $month, 1, $year));
}



// function rateLimitRedis($limit = 100, $window = 60, $identifier = null)
// {
//     // Connect to Redis
//     $redis = new Redis();
//     $redis->connect('127.0.0.1', 6379);

//     // Use IP if no custom identifier provided
//     if ($identifier === null) {
//         $identifier = $_SERVER['REMOTE_ADDR'];
//     }

//     $key = "rate_limit:" . $identifier;

//     // Increment request count
//     $current = $redis->incr($key);

//     // If first request, set expiration
//     if ($current === 1) {
//         $redis->expire($key, $window);
//     }

//     // If limit exceeded
//     if ($current > $limit) {
//         http_response_code(429);
//         header('Content-Type: application/json');
//         echo json_encode([
//             "error" => "Too Many Requests",
//             "limit" => $limit,
//             "window_seconds" => $window
//         ]);
//         exit;
//     }
// }

function getDatabaseSize()
{
    $host = 'localhost';
    $db   = 'pointagedb';
    $user = 'root';
    $pass = '';
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $sql = "SELECT 
                SUM(data_length + index_length) / 1024 / 1024 AS total_size 
            FROM information_schema.TABLES 
            WHERE table_schema = :db_name";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':db_name' => 'pointagedb']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return round($result['total_size'], 2); // Returns size in MB
}

function getTableSize($table)
{
    $host = 'localhost';
    $db   = 'pointagedb';
    $user = 'root';
    $pass = '';
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $stmt = $pdo->prepare("SELECT ROUND((data_length + index_length) / 1024 / 1024, 2) AS size 
                       FROM information_schema.TABLES 
                       WHERE table_schema = 'pointagedb' AND table_name = '$table'");
    $stmt->execute();
    $tableSize = $stmt->fetchColumn();
    return round($tableSize, 2); // Returns size in MB
}



function logActivity($pdo, $action, $idFiscal, $data, $m = null, $a = null, $by = null)
{
    $sql = "INSERT INTO activity_logs (action, idFiscal, details, mois, annee,updated_by) VALUES (?, ?, ?, ?, ?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $action,
        $idFiscal,
        json_encode($data),
        $m,
        $a,
        $by
    ]);
}


function getParam($pdo, $key)
{
    try {
        $stmt = $pdo->prepare("SELECT `valueP` FROM `parametres` WHERE `keyP` = :key LIMIT 1");
        $stmt->execute(['key' => $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['valueP'] : null;
    } catch (PDOException $e) {
        // Log error silently in production
        echo $e->getMessage();
        return null;
    }
}


/**
 * Set or Update a dynamic parameter value by its key
 * @param PDO $pdo Your database connection
 * @param string $key The key name (e.g., 'PasswordRegex')
 * @param string $value The value to store
 * @return bool Returns true on success, false on failure
 */
function setParam($pdo, $key, $value)
{
    try {
        $sql = "INSERT INTO `parametres` (`keyP`, `valueP`) 
                VALUES (:key, :value) 
                ON DUPLICATE KEY UPDATE `valueP` = :value_update";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'key'          => $key,
            'value'        => $value,
            'value_update' => $value
        ]);
    } catch (PDOException $e) {
        // You can log $e->getMessage() here if needed for debugging
        echo $e->getMessage();
        return false;
    }
}
