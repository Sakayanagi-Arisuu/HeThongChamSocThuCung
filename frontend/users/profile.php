<?php
session_start();
require_once "../../includes/db.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../../../frontend/auth/login.php");
    exit;
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Hàm dịch vai trò sang tiếng Việt
function roleToVietnamese($role) {
    switch ($role) {
        case 'admin': return 'Quản trị viên';
        case 'doctor': return 'Bác sĩ';
        case 'customer': return 'Khách hàng';
        case 'guest': return 'Khách';
        default: return $role;
    }
}

// Update info
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $full_name = trim($_POST['full_name']);
    $contact_info = trim($_POST['contact_info']);
    $stmt2 = $conn->prepare("UPDATE users SET full_name=?, contact_info=? WHERE username=?");
    $stmt2->bind_param("sss", $full_name, $contact_info, $username);
    if ($stmt2->execute()) {
        $_SESSION['full_name'] = $full_name;
        $_SESSION['contact_info'] = $contact_info;
        $success = "Cập nhật thông tin thành công!";
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "Có lỗi xảy ra, vui lòng thử lại.";
    }
    $stmt2->close();
}

// Upload avatar (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $uploadDir = '../../uploads/avatars/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $file = $_FILES['avatar'];
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif'];
    if (in_array($fileExt, $allowed)) {
        $newName = $username . '_' . time() . '.' . $fileExt;
        $savePath = $uploadDir . $newName;
        if (move_uploaded_file($file['tmp_name'], $savePath)) {
            $relPath = '/HeThongChamSocThuCung/uploads/avatars/' . $newName;
            $stmt = $conn->prepare("UPDATE users SET avatar=? WHERE username=?");
            $stmt->bind_param("ss", $relPath, $username);
            $stmt->execute();
            $_SESSION['avatar'] = $relPath;
            echo $relPath;
            exit;
        } else {
            http_response_code(400); echo "Tải lên thất bại."; exit;
        }
    } else {
        http_response_code(400); echo "File không hợp lệ."; exit;
    }
}

$avatar = !empty($user['avatar']) ? $user['avatar'] : '/HeThongChamSocThuCung/images/download.jpg';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân - Pet Care Services</title>
    <style>
        body { font-family: Arial; background: #f9f9f9;}
        .profile-box {max-width:450px; margin:40px auto 0; background:#fff; border-radius:10px; box-shadow:0 3px 12px #0001; padding:32px;}
        .profile-box h2 {color: #0077a3;}
        .avatar {width:110px; height:110px; border-radius:50%; border:2px solid #ddd; object-fit:cover; margin-bottom:10px;}
        .profile-box form {margin-top:15px;}
        .profile-box label {display:block; margin-bottom:5px; font-weight:bold;}
        .profile-box input[type="text"], .profile-box input[type="email"] {width:100%; padding:9px 12px; border:1px solid #ccc; border-radius:6px; margin-bottom:14px;}
        .profile-box input[readonly] {background: #f3f3f3;}
        .profile-box button, .profile-box .back-btn {background:#0099cc; color:#fff; border:none; border-radius:6px; padding:10px 24px; font-size:16px; font-weight:bold; cursor:pointer; margin-right:8px;}
        .profile-box .back-btn {background:#ccc; color:#222;}
        .profile-box .back-btn:hover {background:#bbb;}
        .profile-box button[type="submit"]:hover {background:#0077a3;}
        .msg-success {color:green;}
        .msg-error {color:red;}
        .avatar-btn {background:#eee; color:#222; border:1px solid #ccc; font-size:13px; border-radius:6px; padding:5px 13px; cursor:pointer;}
    </style>
</head>
<body>
    <div class="profile-box">
        <h2>Thông tin cá nhân</h2>
        <div style="text-align:center;">
            <img src="<?= htmlspecialchars($avatar) ?>?v=<?= time() ?>" alt="Avatar" class="avatar" id="avatarPreview">
            <form id="avatarForm" enctype="multipart/form-data" style="margin-bottom:10px;">
                <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;" onchange="uploadAvatar()">
                <button type="button" class="avatar-btn" onclick="document.getElementById('avatarInput').click()">Đổi ảnh đại diện</button>
            </form>
        </div>
        <?php if (!empty($success)) echo '<div class="msg-success">'.$success.'</div>'; ?>
        <?php if (!empty($error)) echo '<div class="msg-error">'.$error.'</div>'; ?>
        <form method="POST">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" readonly>
            <label>Họ và tên</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            <label>Email/Số điện thoại liên hệ</label>
            <input type="text" name="contact_info" value="<?= htmlspecialchars($user['contact_info']) ?>" required>
            <label>Vai trò</label>
            <input type="text" value="<?= roleToVietnamese($user['role']) ?>" readonly>
            <button type="submit" name="update_info">Lưu thông tin</button>
            <button type="button" class="back-btn" onclick="window.history.back();">Trở về</button>
        </form>
    </div>
    <script>
        function uploadAvatar() {
            var formData = new FormData(document.getElementById('avatarForm'));
            fetch('', { method: 'POST', body: formData })
            .then(res => res.text())
            .then(src => {
                // Chỉ set lại src nếu server trả về đúng path tuyệt đối
                if(src.startsWith('/HeThongChamSocThuCung/uploads/avatars/')) {
                    document.getElementById('avatarPreview').src = src + '?v=' + Date.now();
                } else {
                    alert(src);
                }
            });
        }
    </script>
</body>
</html>
