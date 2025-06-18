<?php
// File: customer_dashboard.php
session_start();
require_once "../../../includes/db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'khách hàng') {
    header("Location: ../auth/login.php");
    exit;
}

// Đường dẫn ảnh đại diện
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
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="../../css/style.css">
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
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
        }
        .actions a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Chào mừng, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h2>
    <div class="profile">
        <img src="<?= htmlspecialchars($avatarPath) ?>" alt="Avatar" class="avatar">
        <form id="avatarForm" enctype="multipart/form-data" style="display:inline;">
            <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;" onchange="uploadAvatar()">
            <button type="button" onclick="document.getElementById('avatarInput').click()">Cập nhật ảnh đại diện</button>
        </form>
        <p><strong>Tên đăng nhập:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
        <p><strong>Liên hệ:</strong> <?= htmlspecialchars($_SESSION['contact_info']) ?></p>
        <p><strong>Vai trò:</strong> <?= htmlspecialchars($_SESSION['role']) ?></p>
    </div>
    <div class="actions">
        <a href="../customer/appointments/book_appointment.php">🗓️ Đặt lịch khám</a>
        <a href="../customer/appointments/index_appointment.php">📖 Xem lịch hẹn</a>
        <a href="../customer/feedback.php">✉️ Gửi phản hồi</a>
        <a href="../auth/logout.php">🚪 Đăng xuất</a>
    </div>

    <script>
        function uploadAvatar() {
            const formData = new FormData(document.getElementById('avatarForm'));
            formData.append("username", "<?= $_SESSION['username'] ?>");

            fetch('../../api/dashboards/upload_avatar.php', {
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
