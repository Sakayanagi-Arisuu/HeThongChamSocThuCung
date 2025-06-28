<?php
require_once "../../../../includes/db.php";
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'khách hàng') {
    echo "Truy cập bị từ chối!";
    exit;
}

$success = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $appointment_id = $_POST['appointment_id'];
    $cancel_reason = $_POST['cancel_reason'];
    $username = $_SESSION['username'];

    // Lấy ID khách hàng
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user_result = $stmt->get_result();

    if ($user_result->num_rows === 1) {
        $user = $user_result->fetch_assoc();
        $customer_id = $user['id'];

        // Lấy thông tin lịch hẹn để kiểm tra trạng thái
        $check = $conn->prepare("SELECT status FROM appointments WHERE id = ? AND customer_id = ?");
        $check->bind_param("ii", $appointment_id, $customer_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows === 1) {
            $appointment = $result->fetch_assoc();

            if ($appointment['status'] === 'Đã đặt') {
                // Cho phép hủy
                $update = $conn->prepare("UPDATE appointments SET status = 'Đã hủy', reason = ? WHERE id = ?");
                $update->bind_param("si", $cancel_reason, $appointment_id);

                if ($update->execute()) {
                    $success = true;
                } else {
                    $error_message = "Lỗi khi cập nhật trạng thái.";
                }

                $update->close();
            } else {
                // Không cho phép hủy nếu không phải 'Đã đặt'
                $error_message = "Không được phép huỷ khi đã check-in!";
            }
        } else {
            $error_message = "Lịch hẹn không tồn tại hoặc không thuộc về bạn.";
        }

        $check->close();
    }

    $stmt->close();
}
?>

<?php if ($success): ?>
    <script>
        alert("Lịch hẹn đã được hủy thành công!");
        window.location.href = "index_appointment.php";
    </script>
<?php elseif (!empty($error_message)): ?>
    <script>
        alert("<?php echo $error_message; ?>");
        window.location.href = "index_appointment.php";
    </script>
<?php else: ?>
<!-- Giao diện nhập lý do hủy -->
<form method="POST">
    <h2>Hủy lịch hẹn</h2>
    <input type="hidden" name="appointment_id" value="<?php echo $_GET['id'] ?? ''; ?>">
    Lý do hủy:<br>
    <textarea name="cancel_reason" required></textarea><br><br>
    <input type="submit" value="Xác nhận hủy">
</form>
<?php endif; ?>
