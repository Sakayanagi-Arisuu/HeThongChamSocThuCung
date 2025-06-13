<?php
require_once "../../../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $rawPassword = $_POST['password'];
    $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT); // Băm mật khẩu

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "Đăng ký thành công !";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
    $stmt->close();
}
?>
<form method="POST">
    <h2>Đăng ký</h2>
    Tên đăng nhập: <input type="text" name="username" required><br>
    Mật khẩu: <input type="password" name="password" required><br>
    <input type="submit" value="Đăng ký">
    <p>Bạn đã có tài khoản?</p>
    <p><a href ="login.php" target ="_self">Đăng nhập</a></p>  
</form>
<link rel="stylesheet" href="css/style.css">
