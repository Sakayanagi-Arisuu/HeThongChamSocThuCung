<?php
require_once "D:/Xampp/htdocs/HeThongChamSocThuCung/includes/db.php";
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

            // Điều hướng theo role
            switch ($user['role']) {
                case 'admin':
                    header('Location: ../../../backend/api/dashboards/admin_dashboard.php');
                    exit;
                case 'bác sĩ':
                    header('Location: ../../backend/api/dashboard_bacsi.php');
                    exit;
                case 'nhân viên':
                    header('Location: ../../backend/api/dashboard_nhanvien.php');
                    exit;
                case 'khách hàng':
                    header('Location: ../../../backend/api/customer');
                    exit;
                default:
                    echo "Vai trò không hợp lệ.";
            }
        } else {
            echo "Sai mật khẩu!";
        }
    } else {
        echo "Sai tên đăng nhập!";
    }

    $stmt->close();
}
?>

<!-- Giao diện đăng nhập -->
<form method="POST">
    <h2>Đăng nhập</h2>
    Tên đăng nhập: <input type="text" name="username" required><br>
    Mật khẩu: <input type="password" name="password" required><br>
    <input type="submit" value="Đăng nhập">
    <p>Bạn chưa có tài khoản?</p>
    <p><a href="register.php">Đăng ký</a></p>
</form>

<link rel="stylesheet" href="css/style.css">
