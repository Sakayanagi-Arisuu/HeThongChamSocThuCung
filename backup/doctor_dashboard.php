<?php
session_start();
require_once "../../../includes/db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'bác sĩ') {
    header("Location: ../../auth/login.php"); // sửa đường dẫn login cho đúng
    exit;
}

// Nếu không có avatar hoặc file không tồn tại -> dùng ảnh mặc định
$defaultAvatarUrl = '../../../images/download.jpg';
$avatarPath = $defaultAvatarUrl;

if (!empty($_SESSION['avatar'])) {
    $avatarFile = __DIR__ . '/../../../' . $_SESSION['avatar'];  // Đường dẫn file thực tế để kiểm tra tồn tại
    if (file_exists($avatarFile)) {
        $avatarPath = '../../../' . $_SESSION['avatar'];          // URL dùng cho trình duyệt
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
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
            margin-right: 10px;
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
    <h2>Chào Bác sĩ, <?= htmlspecialchars($_SESSION['full_name']) ?></h2>
    <div class="profile">
        <!-- Avatar -->
        <img id="avatarImage" src="<?= htmlspecialchars($avatarPath) ?>" alt="Avatar" class="avatar">
        <form id="avatarForm" enctype="multipart/form-data" style="display:inline;">
            <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;" onchange="uploadAvatar()">
            <button type="button" onclick="document.getElementById('avatarInput').click()">Cập nhật ảnh đại diện</button>
        </form>

        <p><strong>Tên đăng nhập:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
        <p><strong>Liên hệ:</strong> <?= htmlspecialchars($_SESSION['contact_info']) ?></p>
    </div>
    <div class="actions">
        <a href="../doctor/schedules/schedule.php">Xem lịch khám</a>
        <a href="../auth/logout.php">Đăng xuất</a>
    </div>
</body>
<script>
function uploadAvatar() {
    const input = document.getElementById('avatarInput');
    const formData = new FormData();
    formData.append('avatar', input.files[0]);

    fetch('upload_avatar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        if (result.includes("uploads/avatars")) {
            document.getElementById('avatarImage').src = "../../../" + result + "?t=" + new Date().getTime(); // reload ảnh
        } else {
            alert(result); // lỗi
        }
    })
    .catch(error => {
        console.error("Upload error:", error);
    });
}
</script>
</html>
