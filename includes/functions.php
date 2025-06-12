<?php
session_start();
function is_logged_in() {
    return isset($_SESSION['username']);
}

function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}
?>
