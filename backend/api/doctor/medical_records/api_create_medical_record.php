<?php
require_once '../../../../includes/db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    echo json_encode(['success'=>false, 'error'=>'Bạn không có quyền!']); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false, 'error'=>'Phương thức không hợp lệ!']); exit;
}

$appointment_id = intval($_POST['appointment_id'] ?? 0);
$diagnosis = $_POST['diagnosis'] ?? '';
$treatment = $_POST['treatment'] ?? '';
$notes = $_POST['notes'] ?? '';

// --- Lấy loại dịch vụ từ lịch hẹn ---
$stmt = $conn->prepare("SELECT service FROM appointments WHERE id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    echo json_encode(['success'=>false, 'error'=>'Lịch hẹn không tồn tại!']); exit;
}
$service = $result->fetch_assoc()['service'];

// --- Xác định phí dịch vụ ---
$fee = 0;
if ($service === 'Khám tổng quát') $fee = 400000;
else if ($service === 'Khám chuyên khoa') $fee = 300000;
else if ($service === 'Tiêm phòng') $fee = 200000;
else if ($service === 'Tắm, vệ sinh, cắt tỉa lông') $fee = 100000;
else if ($service === 'Khác') {
    $fee = floatval($_POST['fee'] ?? 0);
    if ($fee <= 0) {
        echo json_encode(['success'=>false, 'error'=>'Vui lòng nhập phí dịch vụ hợp lệ!']); exit;
    }
}

// --- Ghi hồ sơ khám ---
$stmt = $conn->prepare("INSERT INTO medical_records (appointment_id, diagnosis, treatment, notes) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $appointment_id, $diagnosis, $treatment, $notes);

if ($stmt->execute()) {
    // Update status, fee, và trạng thái thanh toán trong bảng appointments
    $stmt2 = $conn->prepare("UPDATE appointments SET status='Đã khám', fee=?, payment_status='Chưa thanh toán' WHERE id=?");
    $stmt2->bind_param("di", $fee, $appointment_id);
    $stmt2->execute();
    $stmt2->close();

    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false, 'error'=>$stmt->error]);
}
?>
