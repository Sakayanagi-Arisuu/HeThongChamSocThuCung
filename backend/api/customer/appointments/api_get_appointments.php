<?php
require_once '../../../../includes/db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Unauthorized']);
    http_response_code(401);
    exit;
}

$user_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$user_stmt->bind_param("s", $_SESSION['username']);
$user_stmt->execute();
$user_id = $user_stmt->get_result()->fetch_assoc()['id'];

// Lấy thông tin appointment + doctor + đã feedback chưa (has_feedback) + số sao feedback_rating
$sql = "SELECT 
            a.*, 
            p.name as pet_name, 
            d.username as doctor_name, 
            d.id as doctor_id,
            IF(f.id IS NULL, 0, 1) as has_feedback,
            f.rating as feedback_rating
        FROM appointments a
        JOIN pets p ON a.pet_id = p.id
        JOIN users d ON a.doctor_id = d.id
        LEFT JOIN feedbacks f ON f.appointment_id = a.id AND f.customer_id = ?
        WHERE a.customer_id = ?
        ORDER BY a.schedule_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) $data[] = $row;
echo json_encode($data);
?>
