<?php
session_start();
require_once "../../../includes/db.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $uploadDir = '../../../uploads/avatars/';
    $webPathPrefix = '/HeThongChamSocThuCung/uploads/avatars/';
    $username = $_SESSION['username'];

    // Tạo thư mục nếu chưa có
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmpPath = $_FILES['avatar']['tmp_name'];
    $fileName = basename($_FILES['avatar']['name']);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "Chỉ cho phép ảnh JPG, PNG, GIF.";
        exit;
    }

    $newFileName = $username . "_" . time() . "." . $fileExtension;
    $destination = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destination)) {
        // Đường dẫn từ gốc web
        $relativePath = $webPathPrefix . $newFileName;

        // Cập nhật vào DB
        $stmt = $conn->prepare("UPDATE users SET avatar = ? WHERE username = ?");
        $stmt->bind_param("ss", $relativePath, $username);
        $stmt->execute();

        // Cập nhật session
        $_SESSION['avatar'] = $relativePath;
        echo $relativePath;
    } else {
        echo "Không thể tải ảnh lên.";
    }
}
?>