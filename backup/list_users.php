<?php
session_start();
require_once "../../../../includes/db.php";

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Truy cập bị từ chối!";
    exit;
}

$result = $conn->query("SELECT id, username, role FROM users");

echo "<h2>Danh sách người dùng</h2>";
echo "<a href='add_user.php'>+ Thêm người dùng</a><br><br>";

echo "<table border='1'>
<tr><th>ID</th><th>Tên đăng nhập</th><th>Vai trò</th><th>Thao tác</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['username']}</td>
        <td>{$row['role']}</td>
        <td>
            <a href='edit_user.php?id={$row['id']}'>Sửa</a> | 
            <a href='delete_user.php?id={$row['id']}'>Xóa</a>
        </td>
    </tr>";
}

echo "</table>";
?>
