<?php
require_once '../../../../includes/db.php';
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['username'])) {
    echo json_encode(['success'=>false, 'error'=>'Chưa đăng nhập']); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy user id từ session
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $customer_id = $user['id'];

    $id = $_POST['id'];
    $pet_id = $_POST['pet_id'];
    $doctor_id = $_POST['doctor_id'];
    $schedule_time = $_POST['schedule_time'];
    $reason = $_POST['reason'];

    // Chỉ update khi lịch hẹn của user và status là 'Đã đặt'
    $stmt = $conn->prepare("UPDATE appointments 
        SET pet_id=?, doctor_id=?, schedule_time=?, reason=? 
        WHERE id=? AND customer_id=? AND status='Đã đặt'");
    $stmt->bind_param("iissii", $pet_id, $doctor_id, $schedule_time, $reason, $id, $customer_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false, 'error'=>'Không thể sửa (chỉ sửa lịch hẹn trạng thái Đã đặt của bạn)']);
    }
    $stmt->close();
    exit;
}
echo json_encode(['success'=>false, 'error'=>'Phương thức không hợp lệ']);
?>
