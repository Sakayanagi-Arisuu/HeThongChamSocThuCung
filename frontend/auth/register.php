<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký - Pet Care Services</title>
    <!-- css -->
    <link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/auth/register.css">
</head>
<body>
    <?php
    include __DIR__ . '/../../includes/header.php';
    include __DIR__ . '/../../includes/navbar_guest.php';
    ?>

    <div class="register-container">
        <form class="register-form" id="registerForm" autocomplete="off">
            <h2>Đăng ký</h2>
            <div id="message"></div>
            <input type="text" name="full_name" placeholder="Họ và tên" required>
            <input type="text" name="contact_info" placeholder="Liên hệ (SĐT hoặc Email)" required>
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng ký</button>
            <a href="login.php">Đã có tài khoản? Đăng nhập</a>
        </form>
    </div>
        <!-- script -->
    <script src="/HeThongChamSocThuCung/assets/js/auth/register.js"></script>
</body>
</html>
