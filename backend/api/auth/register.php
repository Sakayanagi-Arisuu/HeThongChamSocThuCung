<?php
require_once "../../../includes/db.php";

$message = ""; // Biến để chứa thông báo
$messageClass = ""; // Class CSS cho thông báo

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $rawPassword = $_POST['password'];
    $fullName = trim($_POST['full_name']);
    $contactInfo = trim($_POST['contact_info']);
    $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

    // Kiểm tra username đã tồn tại chưa
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $message = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
        $messageClass = "error";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, contact_info) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashedPassword, $fullName, $contactInfo);

        if ($stmt->execute()) {
            $message = "Đăng ký thành công!";
            $messageClass = "success";
        } else {
            $message = "Lỗi: " . $stmt->error;
            $messageClass = "error";
        }
        $stmt->close();
    }

    $checkStmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .message {
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Đăng ký</h2>

        <?php if (!empty($message)) : ?>
            <div class="message <?= $messageClass ?>"><?= $message ?></div>
        <?php endif; ?>

        Họ và tên: <input type="text" name="full_name" required><br>
        Liên hệ (SĐT hoặc Email): <input type="text" name="contact_info" required><br>
        Tên đăng nhập: <input type="text" name="username" required><br>
        Mật khẩu: <input type="password" name="password" required><br>
        <input type="submit" value="Đăng ký">
        <p>Bạn đã có tài khoản?</p>
        <p><a href="login.php" target="_self">Đăng nhập</a></p>  
    </form>
</body>
</html>

