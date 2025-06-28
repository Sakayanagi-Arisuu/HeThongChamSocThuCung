<?php
require_once '../../../../includes/db.php';
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['username'])) {
    echo json_encode(['success'=>false, 'error'=>'Chưa đăng nhập']); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $customer_id = $user['id'];

    $id = $_POST['id'];
    // Chỉ check-in được lịch hẹn của mình và đang ở trạng thái 'Đã đặt'
    $stmt = $conn->prepare("UPDATE appointments SET status='Check-in' WHERE id=? AND customer_id=? AND status='Đã đặt'");
    $stmt->bind_param("ii", $id, $customer_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false, 'error'=>'Không thể check-in (chỉ lịch hẹn ĐÃ ĐẶT của bạn)']);
    }
    $stmt->close();
    exit;
}
echo json_encode(['success'=>false, 'error'=>'Phương thức không hợp lệ']);
?>