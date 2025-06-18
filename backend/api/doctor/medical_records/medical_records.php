<?php
require_once "../../../../includes/db.php";
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'bác sĩ') {
    echo "Truy cập bị từ chối.";
    exit;
}

if (!isset($_GET['appointment_id'])) {
    echo "Chưa có lịch hẹn.";
    exit;
}

$appointment_id = intval($_GET['appointment_id']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $diagnosis = $_POST['diagnosis'] ?? '';
    $treatment = $_POST['treatment'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $fee = floatval($_POST['fee'] ?? 0);

    // Ghi hồ sơ khám
    $stmt = $conn->prepare("INSERT INTO medical_records (appointment_id, diagnosis, treatment, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $appointment_id, $diagnosis, $treatment, $notes);

    if ($stmt->execute()) {
        // Cập nhật trạng thái lịch hẹn thành "Đã khám"
        $updateStmt = $conn->prepare("UPDATE appointments SET status = 'Đã khám' WHERE id = ?");
        $updateStmt->bind_param("i", $appointment_id);
        $updateStmt->execute();

        // Thêm dòng thanh toán
        $paymentStmt = $conn->prepare("INSERT INTO payments (appointment_id, amount, status) VALUES (?, ?, 'Chưa thanh toán')");
        $paymentStmt->bind_param("id", $appointment_id, $fee);
        $paymentStmt->execute();

        echo "Đã lưu hồ sơ và tạo thông tin thanh toán.";
        echo '<br><a href="../schedules/schedule.php">Quay lại lịch khám</a>';
        exit;
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>

<h2>Ghi nhận thông tin khám bệnh</h2>
<form method="post">
    <label>Chẩn đoán:</label><br>
    <textarea name="diagnosis" rows="4" cols="50" required></textarea><br><br>

    <label>Lịch trình điều trị:</label><br>
    <textarea name="treatment" rows="4" cols="50" required></textarea><br><br>

    <label>Ghi chú thêm:</label><br>
    <textarea name="notes" rows="4" cols="50"></textarea><br><br>

    <label>Phí dịch vụ (VNĐ):</label><br>
    <input type="number" name="fee" min="0" step="1000" required><br><br>

    <button type="submit">Lưu hồ sơ khám</button>
</form>
