<?php
session_start();
require_once "../../../../includes/db.php";
header('Content-Type: application/json');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success'=>false,'error'=>'Bạn không có quyền truy cập!']); exit;
}
$id = intval($_POST['id'] ?? 0);
$full_name = trim($_POST['full_name'] ?? '');
$role = $_POST['role'] ?? '';
$password = $_POST['password'] ?? '';

if (!$id || !$role) {
    echo json_encode(['success'=>false,'error'=>'Thiếu thông tin!']); exit;
}

if ($password) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET full_name=?, role=?, password=? WHERE id=?");
    $stmt->bind_param("sssi", $full_name, $role, $hashed, $id);
} else {
    $stmt = $conn->prepare("UPDATE users SET full_name=?, role=? WHERE id=?");
    $stmt->bind_param("ssi", $full_name, $role, $id);
}
if ($stmt->execute()) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false, 'error'=>$stmt->error]);
}
$stmt->close();
?>
