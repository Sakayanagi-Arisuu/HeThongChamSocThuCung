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

    $pet_id = $_POST['pet_id'];
    $doctor_id = $_POST['doctor_id'];
    $schedule_time = $_POST['schedule_time'];
    $reason = $_POST['reason'];
    $phone = $_POST['phone'];
    $service = $_POST['service'];

    $stmt = $conn->prepare("INSERT INTO appointments (pet_id, customer_id, phone, doctor_id, schedule_time, service, status, reason)
                            VALUES (?, ?, ?, ?, ?, ?, 'Đã đặt', ?)");
    $stmt->bind_param("iisssss", $pet_id, $customer_id, $phone, $doctor_id, $schedule_time, $service, $reason);

    if ($stmt->execute()) {
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false, 'error'=>$stmt->error]);
    }
    $stmt->close();
    exit;
}
echo json_encode(['success'=>false, 'error'=>'Phương thức không hợp lệ']);
?>
