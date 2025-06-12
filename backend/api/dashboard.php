<?php
require_once "includes/functions.php";
redirect_if_not_logged_in();

$role = $_SESSION['role'];
$username = $_SESSION['username'];
$message = "Xin chào " . ucfirst($role) . " " . ($username) . " !";
?>
<h2><?php echo $message; ?></h2>
<p><a href="logout.php">Đăng xuất</a></p>
<link rel="stylesheet" href="css/style.css">
