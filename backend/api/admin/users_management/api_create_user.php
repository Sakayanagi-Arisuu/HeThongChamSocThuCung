<?php
session_start();
require_once "../../../../includes/db.php";
header('Content-Type: application/json');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success'=>false,'error'=>'Bạn không có quyền truy cập!']); exit;
}
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';
$full_name = trim($_POST['full_name'] ?? '');

if (!$username || !$password || !$role) {
    echo json_encode(['success'=>false,'error'=>'Thiếu thông tin!']); exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success'=>false,'error'=>'Username đã tồn tại!']); exit;
}
$stmt->close();

$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password, role, full_name) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $hashed, $role, $full_name);
if ($stmt->execute()) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false, 'error'=>$stmt->error]);
}
$stmt->close();
?>
