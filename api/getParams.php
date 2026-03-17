<?php
include 'conf.php';
include 'helpers.php';


$key   = $_POST['key'] ?? '';

$value = getParam($pdo, $key);


if ($value) {
    // Use it in your validation logic
    echo json_encode([
        'success' => true,
        'message' => $value
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => "Element Introuvable"
    ]);
}
