<?php
session_start();
require_once "../../../../includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        echo "Thêm người dùng thành công! <a href='list_users.php'>Quay lại</a>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
}
?>

<h2>Thêm người dùng mới</h2>
<form method="POST">
    Tên đăng nhập: <input type="text" name="username" required><br>
    Mật khẩu: <input type="password" name="password" required><br>
    Vai trò:
    <select name="role">
        <option value="admin">Admin</option>
        <option value="bác sĩ">Bác sĩ</option>
        <option value="nhân viên">Nhân viên</option>
        <option value="khách hàng">Khách hàng</option>
    </select><br>
    <input type="submit" value="Thêm">
</form>
