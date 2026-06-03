<?php
/*
// 1. Send JSON Headers and Set Access Security Layout
header('Content-Type: application/json');

require_once 'conf.php';      // Uses your existing database configuration ($pdo)
require_once 'helpers.php';   // Uses your authentication and authorization helpers

session_start();
isAuthQuery();   // Protect the endpoint from unauthenticated access
isAdminQuery();  // Optional: Restrict file uploads to admins only (remove if any user can upload)

// 2. Handle File Verification via POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed. Use POST.']);
    exit;
}

if (!isset($_FILES['csv_file'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No CSV file payload detected under key "csv_file".']);
    exit;
}

$file = $_FILES['csv_file']['tmp_name'];

if (($handle = fopen($file, "r")) === FALSE) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to read or parse the uploaded temporary file.']);
    exit;
}

try {
    // Read the first row to determine structural headers
    $headers = fgetcsv($handle, 1000, ",");

    // --- DETECT DELIMITER (Semicolon adaptation for Excel formats) ---
    if (count($headers) == 1 && strpos($headers[0], ';') !== false) {
        rewind($handle);
        $headers = fgetcsv($handle, 1000, ";");
        $delimiter = ";";
    } else {
        $delimiter = ",";
    }

    // --- CLEAN UP HEADERS ---
    // Strip hidden Byte Order Marks (BOM) added by Excel exports
    if (isset($headers[0])) {
        $headers[0] = preg_replace('/[\x{00EF}\x{00BB}\x{00BF}\x{FEFF}]/u', '', $headers[0]);
    }

    // Standardize casing and spaces
    $headers = array_map(function ($header) {
        return strtolower(trim($header));
    }, $headers);

    // Map keys to coordinate indexes
    $indexOf = array_flip($headers);

    // Validate absolute dependencies exist
    $requiredKeys = ['scheddate', 'logonid', 'exception', 'the start', 'the stop'];
    $missingKeys = [];
    foreach ($requiredKeys as $key) {
        if (!isset($indexOf[$key])) {
            $missingKeys[] = $key;
        }
    }

    if (!empty($missingKeys)) {
        fclose($handle);
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'error' => 'Missing required structure headers in CSV payload.',
            'missing_columns' => $missingKeys
        ]);
        exit;
    }

    // 3. Prepare Core SQL Execution Loop Statements
    $sql = "INSERT INTO shifts (agentId, shift_type, start_time, end_time, isDeleted) 
            VALUES (:agentId, :shift_type, :start_time, :end_time, :isDeleted)";
    $stmt = $pdo->prepare($sql);

    // Statement for finding real Agent Key allocations from external file variables
    $agentQuery = $pdo->prepare("SELECT id FROM agents WHERE idProx = ? LIMIT 1");

    $rowCount = 0;
    $skippedCount = 0;
    $skippedLogonIds = [];

    // Begin processing CSV dataset rows
    while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {

        // Extract using normalized lowercase key definitions
        $schedDate = trim($data[$indexOf['scheddate']]);
        $logonId   = trim($data[$indexOf['logonid']]);
        $exception = trim($data[$indexOf['exception']]);
        $theStart  = trim($data[$indexOf['the start']]);
        $theStop   = trim($data[$indexOf['the stop']]);

        // Safely ignore trailing or structurally empty array blocks
        if (empty($logonId) && empty($schedDate)) {
            continue;
        }

        // --- STEP 1: Translate Logonid context into Database IDs ---
        $agentQuery->execute([$logonId]);
        $agent = $agentQuery->fetch();
        $agentId = $agent ? $agent['id'] : null;

        if (!$agentId) {
            $skippedCount++;
            if (!in_array($logonId, $skippedLogonIds)) {
                $skippedLogonIds[] = $logonId; // Track unknown user identities
            }
            continue;
        }

        // --- STEP 2: Combine Timestamps into proper MySQL Storage Formats (Y-d-m) ---
        $startTimeStr = $schedDate . ' ' . $theStart;
        $endTimeStr   = $schedDate . ' ' . $theStop;

        // FIXED: Shifted string layout to Y-d-m from alternative Y-d-m to ensure native DB visibility
        $startTime = date('Y-d-m H:i:s', strtotime($startTimeStr));
        $endTime   = date('Y-d-m H:i:s', strtotime($endTimeStr));

        // --- STEP 3: Automated Overnight Window Intercept ---
        if (strtotime($endTime) < strtotime($startTime)) {
            $endTime = date('Y-d-m H:i:s', strtotime($endTimeStr . ' +1 day'));
        }

        // Write metrics directly to target DB
        $stmt->execute([
            ':agentId'    => $agentId,
            ':shift_type' => $exception,
            ':start_time' => $startTime,
            ':end_time'   => $endTime,
            ':isDeleted'  => 0
        ]);

        $rowCount++;
    }

    fclose($handle);

    // 4. Send API Success Payload Message Back to Frontend Engine
    echo json_encode([
        'success' => true,
        'data' => [
            'message' => "CSV imported successfully.",
            'imported_records_count' => $rowCount,
            'skipped_records_count'  => $skippedCount,
            'unresolved_logon_ids'   => $skippedLogonIds
        ]
    ]);
} catch (\PDOException $e) {
    if (isset($handle)) {
        fclose($handle);
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'A fatal database transactional operation error occurred.',
        'debug_message' => $e->getMessage() // Set parameter to hidden or display based on prod rules
    ]);
}
*/

// 1. Send JSON Headers and Set Access Security Layout
header('Content-Type: application/json');

require_once 'conf.php';      // Uses your existing database configuration ($pdo)
require_once 'helpers.php';   // Uses your authentication and authorization helpers

session_start();
isAuthQuery();   // Protect the endpoint from unauthenticated access
isAdminQuery();  // Restrict file uploads to admins only

// 2. Handle File Verification via POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed. Use POST.']);
    exit;
}

if (!isset($_FILES['csv_file'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No CSV file payload detected under key "csv_file".']);
    exit;
}

$file = $_FILES['csv_file']['tmp_name'];

if (($handle = fopen($file, "r")) === FALSE) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to read or parse the uploaded temporary file.']);
    exit;
}

// Define the logging function at the top level or keep it in your helpers.php
function logShiftChange2($pdo, $shiftId, $action, $oldData = null, $newData = null)
{
    $sql = "INSERT INTO shifts_logs (shift_id, action_type, changed_by, old_data, new_data) 
            VALUES (:shift_id, :action, :user_id, :old, :new)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':shift_id' => $shiftId,
        ':action'   => $action,
        ':user_id'  => $_SESSION['id'] ?? null,
        ':old'      => $oldData ? json_encode($oldData) : null,
        ':new'      => $newData ? json_encode($newData) : null
    ]);
}

try {
    // Read the first row to determine structural headers
    $headers = fgetcsv($handle, 1000, ",");

    // --- DETECT DELIMITER (Semicolon adaptation for Excel formats) ---
    if (count($headers) == 1 && strpos($headers[0], ';') !== false) {
        rewind($handle);
        $headers = fgetcsv($handle, 1000, ";");
        $delimiter = ";";
    } else {
        $delimiter = ",";
    }

    // --- CLEAN UP HEADERS ---
    if (isset($headers[0])) {
        $headers[0] = preg_replace('/[\x{00EF}\x{00BB}\x{00BF}\x{FEFF}]/u', '', $headers[0]);
    }

    $headers = array_map(function ($header) {
        return strtolower(trim($header));
    }, $headers);

    $indexOf = array_flip($headers);

    // Validate absolute dependencies exist
    $requiredKeys = ['scheddate', 'logonid', 'exception', 'the start', 'the stop'];
    $missingKeys = [];
    foreach ($requiredKeys as $key) {
        if (!isset($indexOf[$key])) {
            $missingKeys[] = $key;
        }
    }

    if (!empty($missingKeys)) {
        fclose($handle);
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'error' => 'Missing required structure headers in CSV payload.',
            'missing_columns' => $missingKeys
        ]);
        exit;
    }

    // 3. Prepare SQL Statements
    $insertSql = "INSERT INTO shifts (agentId, shift_type, start_time, end_time, isDeleted) 
                  VALUES (:agentId, :shift_type, :start_time, :end_time, :isDeleted)";
    $insertStmt = $pdo->prepare($insertSql);

    $agentQuery = $pdo->prepare("SELECT id FROM agents WHERE idProx = ? LIMIT 1");

    // --- FIX: Duplicate detection query ---
    $checkSql = "SELECT id FROM shifts 
                 WHERE agentId = :agentId 
                   AND start_time = :start_time 
                   AND end_time = :end_time 
                   AND isDeleted = 0 
                 LIMIT 1";
    $checkStmt = $pdo->prepare($checkSql);

    $rowCount = 0;
    $duplicateCount = 0;
    $skippedCount = 0;
    $skippedLogonIds = [];
    $affectedAgents = [];
    // Begin processing CSV dataset rows
    while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {

        $schedDate = trim($data[$indexOf['scheddate']]);
        $logonId   = trim($data[$indexOf['logonid']]);
        $exception = trim($data[$indexOf['exception']]);
        $theStart  = trim($data[$indexOf['the start']]);
        $theStop   = trim($data[$indexOf['the stop']]);

        if (empty($logonId) && empty($schedDate)) {
            continue;
        }

        // --- STEP 1: Translate Logonid context into Database IDs ---
        $agentQuery->execute([$logonId]);
        $agent = $agentQuery->fetch();
        $agentId = $agent ? $agent['id'] : null;

        if (!$agentId) {
            $skippedCount++;
            if (!in_array($logonId, $skippedLogonIds)) {
                $skippedLogonIds[] = $logonId;
            }
            continue;
        }

        // --- STEP 2: Combine Timestamps into proper MySQL Storage Formats (Y-d-m) ---
        $startTimeStr = $schedDate . ' ' . $theStart;
        $endTimeStr   = $schedDate . ' ' . $theStop;

        // FIXED: Using standard Y-d-m layout so MySQL reads dates properly
        $startTime = date('Y-d-m H:i:s', strtotime($startTimeStr));
        $endTime   = date('Y-d-m H:i:s', strtotime($endTimeStr));

        // --- STEP 3: Automated Overnight Window Intercept ---
        if (strtotime($endTime) < strtotime($startTime)) {
            $endTime = date('Y-d-m H:i:s', strtotime($endTimeStr . ' +1 day'));
        }

        // --- STEP 4: Duplicate Prevention Check ---
        $checkStmt->execute([
            ':agentId'    => $agentId,
            ':start_time' => $startTime,
            ':end_time'   => $endTime
        ]);

        if ($checkStmt->fetch()) {
            $duplicateCount++; // Existing record found; increment counter and skip insert
            continue;
        }

        // --- STEP 5: Perform Safe Insertion ---
        $insertStmt->execute([
            ':agentId'    => $agentId,
            ':shift_type' => $exception,
            ':start_time' => $startTime,
            ':end_time'   => $endTime,
            ':isDeleted'  => 0
        ]);

        // Get the generated auto-increment id for logging
        $newShiftId = $pdo->lastInsertId();
        /****** */
        $isoStart = str_replace(' ', 'T', $startTime);
        $isoEnd   = str_replace(' ', 'T', $endTime);
        $logFormattedData = [
            "id"       => (string)$newShiftId,
            "end"      => $isoEnd,
            "start"    => $isoStart,
            "title"    => strtoupper(str_replace('_', ' ', $exception)), // Matches your event title generator
            "agent_id" => (string)$agentId
        ];
        /******* */
        // --- STEP 6: Execute Change Log ---
        // $data matches the current array row parsed out of your CSV file
        logShiftChange($pdo, $newShiftId, 'MASSECREATE', null, $logFormattedData);
        $affectedAgents[$agentId] = true;
        $rowCount++;
    }

    fclose($handle);

    if (!empty($affectedAgents)) {
        // array_keys() turns ['2637' => true, '1044' => true] into [2637, 1044]
        $agentIdsToNotify = array_keys($affectedAgents);

        sendBulkNotification(
            'Planning',
            'Un nouveau shift a été ajouté. Veuillez vérifier votre planning.',
            $agentIdsToNotify,
            $_SESSION['id']
        );
    }

    // 4. Send API Success Payload Message Back to Frontend Engine
    echo json_encode([
        'success' => true,
        'data' => [
            'message' => "CSV file processing completed.",
            'imported_records_count'  => $rowCount,
            'duplicate_records_count' => $duplicateCount,
            'skipped_records_count'   => $skippedCount,
            'unresolved_logon_ids'    => $skippedLogonIds
        ]
    ]);
} catch (\PDOException $e) {
    if (isset($handle)) {
        fclose($handle);
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'A fatal database transactional operation error occurred.',
        'debug_message' => $e->getMessage()
    ]);
}
