<?php
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();


$user_role = $_SESSION['role'];
$user_indice = $_SESSION['id'];

header('Content-Type: application/json');


$root_folders = [];

try {
    if (!isset($_GET['view']) || !in_array($_GET['view'], ['single', 'multiple'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid view parameter"
        ]);
        exit();
    }

    if ($user_role == "A" &&  $_GET['view'] == 'multiple') {
        $sql = "SELECT id, nom, prenom FROM agents ORDER BY nom";
    } else {
        $sql = "SELECT id, nom, prenom FROM agents where id = $user_indice ORDER BY nom";
    }



    $stmt = $pdo->query($sql);

    // 2. Fetch results using PDO::FETCH_ASSOC
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Formatting name and surname
        $folder_name = strtoupper($row['nom']) . "<br>" . ucfirst(strtolower($row['prenom']));

        // Building the array structure
        $root_folders[$folder_name] = [
            'type'    => 'folder',
            'user_id' => $row['id']
            // 'contents' => (object)[],
        ];
    }
} catch (PDOException $e) {
    // Handle errors (optional)
    // echo "Error: " . $e->getMessage();
    echo json_encode([
        "status" => "error",
        "message" => "Error: " . $e->getMessage()
    ]);
}

// 3. Output the JSON
header('Content-Type: application/json');
// echo json_encode($root_folders);

echo json_encode([
    "status" => "success",
    "data" => $root_folders
]);
