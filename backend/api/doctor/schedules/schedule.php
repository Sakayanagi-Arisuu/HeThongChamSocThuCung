<?php
require_once "../../../../includes/db.php";
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'bác sĩ') {
    echo "Truy cập bị từ chối.";
    exit;
}

// Lấy ID của doctor hiện tại
$db_username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $db_username);
$stmt->execute();
$result = $stmt->get_result();
$doctor_id = $result->fetch_assoc()['id'];

// Lấy danh sách các lịch hẹn của doctor hiện tại
$query = "SELECT a.id, a.schedule_time, a.status, a.reason, p.name AS pet_name, u.username AS customer_name
          FROM appointments a
          JOIN pets p ON a.pet_id = p.id
          JOIN users u ON a.customer_id = u.id
          WHERE a.doctor_id = ?
          ORDER BY a.schedule_time ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Lịch khám của bạn</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>Thú cưng</th>
        <th>Khách hàng</th>
        <th>Thời gian</th>
        <th>Trạng thái</th>
        <th>Lý do</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['pet_name']) ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['schedule_time']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= htmlspecialchars($row['reason']) ?></td>
        <td>
            <?php if ($row['status'] === 'Check-in'): ?>
                <a href="../medical_records/medical_records.php?appointment_id=<?= $row['id'] ?>">Khám</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

