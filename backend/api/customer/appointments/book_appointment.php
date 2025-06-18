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

// Lấy thú cưng thuộc user này
$pets = $conn->query("SELECT id, name FROM pets WHERE owner_id = $user_id");

// Lấy danh sách bác sĩ
$doctors = $conn->query("SELECT id, username FROM users WHERE role = 'bác sĩ'");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pet_id = $_POST['pet_id'];
    $doctor_id = $_POST['doctor_id'];
    $schedule_time = $_POST['schedule_time'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO appointments (pet_id, customer_id, doctor_id, schedule_time, status, reason) 
                            VALUES (?, ?, ?, ?, 'Đã đặt', ?)");
    $stmt->bind_param("iiiss", $pet_id, $user_id, $doctor_id, $schedule_time, $reason);

    if ($stmt->execute()) {
        header("Location: index_appointment.php");
        exit;
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>

<h2>Đặt lịch khám</h2>
<form method="POST">
    Thú cưng:
    <select name="pet_id" required>
        <?php while ($row = $pets->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
    </select><br>

    Bác sĩ:
    <select name="doctor_id" required>
        <?php while ($row = $doctors->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['username']) ?></option>
        <?php endwhile; ?>
    </select><br>

    Thời gian khám: <input type="datetime-local" name="schedule_time" required><br>
    Lý do khám: <textarea name="reason" required></textarea><br>
    <input type="submit" value="Đặt lịch">
</form>
