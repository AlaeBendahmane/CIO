<?php

include 'conf.php';
include 'helpers.php';

$key   = $_POST['key'] ?? '';
$value = $_POST['value'] ?? '';
$crypt = $_POST['crypt'] ?? 0;

if ($crypt == 1) {
    $value = md5(md5($value));
}

if (setParam($pdo, $key, $value)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la sauvegarde']);
}
