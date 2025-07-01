<?php
require_once '../../../../includes/db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    echo json_encode(['error' => 'Unauthorized']);
    http_response_code(401);
    exit;
}

// Lấy doctor_id hiện tại
$user_stmt = $conn->prepare("SELECT id, full_name FROM users WHERE username = ?");
$user_stmt->bind_param("s", $_SESSION['username']);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();
$doctor_id = $user['id'];

// Lấy feedbacks về bác sĩ này
$sql = "SELECT f.rating, f.comment, f.created_at, u.full_name as customer_name, a.schedule_time
        FROM feedbacks f
        JOIN users u ON f.customer_id = u.id
        JOIN appointments a ON f.appointment_id = a.id
        WHERE f.doctor_id = ?
        ORDER BY f.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) $data[] = $row;
echo json_encode($data);
?>
