<?php
require_once "includes/db.php";

$users = [
    ['admin', 'admin123', 'admin'],
    ['doctor', 'doc123', 'bác sĩ'],
    ['staff', 'staff123', 'nhân viên']
];

foreach ($users as $user) {
    $username = $user[0];
    $passwordHash = password_hash($user[1], PASSWORD_DEFAULT);
    $role = $user[2];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $passwordHash, $role);
    $stmt->execute();
}

echo "Đã thêm tài khoản mẫu thành công!";
?>
