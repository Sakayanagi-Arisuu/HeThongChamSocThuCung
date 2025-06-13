<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Chào mừng Admin, <?php echo $_SESSION['username']; ?>!</h2>
    <ul>
        <li><a href="../../../backend\api\admin\users_management\list_users.php">Quản lý người dùng</a></li>
        <li><a href="system_settings.php">Cấu hình hệ thống</a></li>
        <li><a href="stats.php">Thống kê doanh thu</a></li>
        <p><a href="../../../backend\api\auth\logout.php">Đăng xuất</a></p>
    </ul>
</body>
</html>

