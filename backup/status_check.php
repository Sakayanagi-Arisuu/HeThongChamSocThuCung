<?php
require_once "../../../../includes/db.php";
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'khách hàng') {
    echo json_encode(["allowed" => false, "message" => "Truy cập bị từ chối"]);
    exit;
}

$appointment_id = $_POST['id'];
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT a.status FROM appointments a
    INNER JOIN users u ON a.customer_id = u.id
    WHERE a.id = ? AND u.username = ?");
$stmt->bind_param("is", $appointment_id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $status = $result->fetch_assoc()['status'];
    echo json_encode([
        "allowed" => $status === 'Đã đặt',
        "status" => $status,
        "message" => ($status === 'Đã đặt') ? "" : "Chỉ có thể thao tác khi trạng thái là 'Đã đặt'!"
    ]);
} else {
    echo json_encode(["allowed" => false, "message" => "Không tìm thấy lịch hẹn"]);
}

$stmt->close();
