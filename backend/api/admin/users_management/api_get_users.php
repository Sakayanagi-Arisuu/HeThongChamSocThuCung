<?php
session_start();
require_once "../../../../includes/db.php";
header('Content-Type: application/json');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error'=>'Bạn không có quyền truy cập!']); exit;
}
// Chỉ lấy user KHÔNG phải admin
$sql = "SELECT id, username, full_name, role FROM users WHERE role <> 'admin' ORDER BY id";
$result = $conn->query($sql);
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
echo json_encode($users);
?>
