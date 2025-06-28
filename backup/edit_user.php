<?php
session_start();
require_once "../../../../includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Không có quyền truy cập!";
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT username, role FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username=?, role=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $role, $id);
    $stmt->execute();

    echo "Cập nhật thành công! <a href='list_users.php'>Quay lại</a>";
    exit;
}
?>

<h2>Sửa người dùng</h2>
<form method="POST">
    Tên đăng nhập: <input type="text" name="username" value="<?= $user['username'] ?>" required><br>
    Vai trò:
    <select name="role">
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="bác sĩ" <?= $user['role'] === 'bác sĩ' ? 'selected' : '' ?>>Bác sĩ</option>
        <option value="nhân viên" <?= $user['role'] === 'nhân viên' ? 'selected' : '' ?>>Nhân viên</option>
        <option value="khách hàng" <?= $user['role'] === 'khách hàng' ? 'selected' : '' ?>>Khách hàng</option>
    </select><br>
    <input type="submit" value="Cập nhật">
</form>
