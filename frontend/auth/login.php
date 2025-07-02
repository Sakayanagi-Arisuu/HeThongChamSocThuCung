<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Pet Care Services</title>
    <!-- css -->
    <link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/auth/login.css">
</head>
<body>
    <?php
    include __DIR__ . '/../../includes/header.php';
    include __DIR__ . '/../../includes/navbar_guest.php';
    ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="login-error">Sai tên đăng nhập hoặc mật khẩu!</div>
    <?php endif; ?>

    <div class="login-container">
        <form class="login-form" method="POST" action="/HeThongChamSocThuCung/backend/api/auth/login.php">
            <h2>Đăng nhập</h2>
            <input type="text" name="username" placeholder="Username" required autocomplete="username">
            <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
            <a href="#">Quên mật khẩu?</a>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
<?php

include '../../includes/footer.php';
?>
</html>
