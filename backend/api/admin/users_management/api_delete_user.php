<?php
session_start();
require_once "../../../../includes/db.php";
header('Content-Type: application/json');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success'=>false,'error'=>'Bạn không có quyền truy cập!']); exit;
}
$id = intval($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['success'=>false,'error'=>'Thiếu ID!']); exit;
}
$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false,'error'=>$stmt->error]);
}
$stmt->close();
?>
