<?php
require_once '../../../includes/db.php';
session_start();

if (!isset($_SESSION['username']) ) {
    header('Location: ../../../auth/login.php');
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM pets WHERE id = $id");
header('Location: pets.php');
