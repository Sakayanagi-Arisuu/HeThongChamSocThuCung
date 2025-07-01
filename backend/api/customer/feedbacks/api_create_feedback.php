<?php
session_start();
require_once "../../../../includes/db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success'=>false, 'error'=>'Bạn chưa đăng nhập!']); exit;
}
$customer_id = $_SESSION['user_id'] ?? 0; // hoặc dùng $_SESSION['id'] tùy session bạn set

// Nhận dữ liệu từ form
$appointment_id = intval($_POST['appointment_id'] ?? 0);
$doctor_id = intval($_POST['doctor_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if (!$appointment_id || !$doctor_id || !$rating) {
    echo json_encode(['success'=>false, 'error'=>'Thiếu thông tin đánh giá!']); exit;
}

// Kiểm tra đã đánh giá chưa (mỗi appointment chỉ cho phép 1 đánh giá)
$stmt = $conn->prepare("SELECT id FROM feedbacks WHERE appointment_id=? AND customer_id=?");
$stmt->bind_param('ii', $appointment_id, $customer_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success'=>false, 'error'=>'Bạn đã đánh giá lịch hẹn này rồi!']); exit;
}
$stmt->close();

// Thêm đánh giá mới
$stmt = $conn->prepare("INSERT INTO feedbacks (customer_id, doctor_id, appointment_id, rating, comment) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('iiiis', $customer_id, $doctor_id, $appointment_id, $rating, $comment);
if ($stmt->execute()) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false, 'error'=>'Lỗi khi lưu đánh giá!']);
}
?>
