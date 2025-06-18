<?php
session_start();
require_once "../../../includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$defaultAvatar = '../../../images/download.jpg';
$avatarPath = $defaultAvatar;

if (!empty($_SESSION['avatar'])) {
    $avatarFile = __DIR__ . '/../../../' . $_SESSION['avatar'];
    if (file_exists($avatarFile)) {
        $avatarPath = '../../../' . $_SESSION['avatar'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 2px solid #ccc;
            object-fit: cover;
        }
        .actions a {
            display: inline-block;
            margin: 8px 10px;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
        }
        .actions a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Chào mừng Admin, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

    <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Avatar" class="avatar">
    <form id="avatarForm" enctype="multipart/form-data" style="display:inline;">
        <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;" onchange="uploadAvatar()">
        <button type="button" onclick="document.getElementById('avatarInput').click()">Cập nhật ảnh đại diện</button>
    </form>

    <ul class="actions">
        <li><a href="../../../backend/api/admin/users_management/list_users.php">Quản lý người dùng</a></li>
        <li><a href="system_settings.php">Cấu hình hệ thống</a></li>
        <li><a href="stats.php">Thống kê doanh thu</a></li>
        <p><a href="../../../backend/api/auth/logout.php">Đăng xuất</a></p>
    </ul>

    <script>
        function uploadAvatar() {
            const formData = new FormData(document.getElementById('avatarForm'));
            formData.append("username", "<?= $_SESSION['username'] ?>");

            fetch('../../../backend/api/dashboards/upload_avatar.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(err => {
                console.error(err);
                alert("Lỗi khi tải ảnh lên.");
            });
        }
    </script>
</body>
</html>
