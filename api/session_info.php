<?php
session_start();
$id = $_SESSION['id'] ?? ""; //
$idFiscal = $_SESSION['idFiscal'] ?? ""; //toprofile
$idProx = $_SESSION['idProx'] ?? ""; //toprofile
$nom = $_SESSION['nom'] ?? ""; //
$prenom = $_SESSION['prenom'] ?? ""; //
$email = $_SESSION['email'] ?? ""; //
$ste = $_SESSION['ste'] ?? ""; //toprofile
$campagne = $_SESSION['campagne'] ?? ""; //
$role = $_SESSION['role'] ?? ""; //
$profilePic = $_SESSION['profilePic'] ?? "";
$token =  $_SESSION['token'] ?? "";
$needReset = $_SESSION['needReset'] ?? 1;
