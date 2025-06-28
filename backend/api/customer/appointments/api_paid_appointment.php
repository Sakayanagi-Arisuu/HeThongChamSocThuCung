<?php
require_once '../../../../includes/db.php';
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['username'])) {
    echo json_encode(['success'=>false, 'error'=>'Chưa đăng nhập!']); exit;
}
$id = intval($_POST['id'] ?? 0);
if (!$id) { echo json_encode(['success'=>false, 'error'=>'Thiếu ID!']); exit; }
// Cập nhật trạng thái thanh toán
$stmt = $conn->prepare("UPDATE appointments SET payment_status='Đã thanh toán', paid_at=NOW() WHERE id=?");
$stmt->bind_param("i", $id);
if($stmt->execute()) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false, 'error'=>$stmt->error]);
}
?>
