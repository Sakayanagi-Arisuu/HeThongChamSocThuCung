<?php
require_once "D:/Xampp/htdocs/HeThongChamSocThuCung/includes/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $inputPassword = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $login_success = false;
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($inputPassword, $user['password'])) {
            // Đăng nhập thành công
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['avatar'] = $user['avatar'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['contact_info'] = $user['contact_info'];

            // Điều hướng theo role
            switch ($user['role']) {
                case 'admin':
                    header('Location: ../../../frontend/admin/admin_dashboard.php');
                    exit;
                case 'doctor':
                    header('Location: ../../../frontend/doctor/doctor_dashboard.php');
                    exit;
                case 'customer':
                    header('Location: ../../../frontend/customer/customer_dashboard.php');
                    exit;
                default:
                    echo "Vai trò không hợp lệ.";
                    exit;
            }
        }
    }

    // Nếu đến đây là sai tên đăng nhập hoặc mật khẩu
    header('Location: /HeThongChamSocThuCung/frontend/auth/login.php?error=1');
    exit;
}

// Nếu muốn giữ lại form đăng nhập ở đây khi truy cập trực tiếp
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
?>
    <form method="POST">
        <h2>Đăng nhập</h2>
        <?php if (isset($_GET['error'])): ?>
            <div style="color: red; margin-bottom: 10px; font-weight: bold;">
                Sai tên đăng nhập hoặc mật khẩu!
            </div>
        <?php endif; ?>
        Tên đăng nhập: <input type="text" name="username" required><br>
        Mật khẩu: <input type="password" name="password" required><br>
        <input type="submit" value="Đăng nhập">
        <p>Bạn chưa có tài khoản?</p>
        <p><a href="register.php">Đăng ký</a></p>
    </form>
    <link rel="stylesheet" href="css/style.css">
<?php
}
?>
