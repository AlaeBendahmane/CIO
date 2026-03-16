<?php
require_once 'conf.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

try {
    $stmt = $pdo->prepare("SELECT abreviation, nomRole FROM roles WHERE isDeleted= 0 ORDER BY id ASC");
    $stmt->execute();
    $rolesList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $rolesList = []; // Fallback to empty array on error
}



try {
    $stmt = $pdo->prepare("SELECT abreviation, nomSte FROM ste WHERE isDeleted= 0 ORDER BY id ASC");
    $stmt->execute();
    $steList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $steList = []; // Fallback to empty array on error
}



try {
    $stmt = $pdo->prepare("SELECT abreviation, nomCompagne FROM compagne WHERE isDeleted= 0 ORDER BY id ASC");
    $stmt->execute();
    $compagneList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $compagneList = []; // Fallback to empty array on error
}
