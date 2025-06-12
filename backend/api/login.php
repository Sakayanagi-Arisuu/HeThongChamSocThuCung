<?php
require_once "includes/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $inputPassword = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($inputPassword, $user['password'])) {
            // Đăng nhập thành công
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Sai mật khẩu!";
        }
    } else {
        echo "Sai tên đăng nhập!";
    }

    $stmt->close();
}
?>
<form method="POST">
    <h2>Đăng nhập</h2>
    Tên đăng nhập: <input type="text" name="username" required><br>
    Mật khẩu: <input type="password" name="password" required><br>
    <input type="submit" value="Đăng nhập">
    <p>Bạn chưa có tài khoản?</p>
    <p><a href ="register.php" target ="_self">Đăng ký</a></p>
</form>
<link rel="stylesheet" href="css/style.css">
