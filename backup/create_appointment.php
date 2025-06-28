<?php
require_once "../../../../includes/db.php";
session_start();

if (!isset($_SESSION['username'])) {
    echo "Truy cập bị từ chối.";
    exit;
}

// Lấy user id hiện tại
$current_user = $_SESSION['username'];
$user_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$user_stmt->bind_param("s", $current_user);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_id = $user_result->fetch_assoc()['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pet_id = $_POST['pet_id'];
    $phone = $_POST['phone'];
    $doctor_id = $_POST['doctor_id'];
    $schedule_time = $_POST['schedule_time'];
    $service = $_POST['service'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO appointments (pet_id, customer_id, phone, doctor_id, schedule_time, service, status, reason) 
                            VALUES (?, ?, ?, ?, ?, ?, 'Đã đặt', ?)");
    $stmt->bind_param("iisssss", $pet_id, $user_id, $phone, $doctor_id, $schedule_time, $service, $reason);

    if ($stmt->execute()) {
        echo "<script>alert('Đặt lịch thành công!'); window.location.href='../../../../frontend/appointments/appointments.php';</script>";
        exit;
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>
