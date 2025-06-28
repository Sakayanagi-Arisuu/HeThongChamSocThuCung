<?php
require_once "../../../../includes/db.php";
session_start();

if (!isset($_SESSION['username'])) {
    echo "Truy cập bị từ chối.";
    exit;
}

// Lấy ID người dùng đang đăng nhập
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$resultUser = $stmt->get_result();
$user = $resultUser->fetch_assoc();
$userId = $user['id'];

// Lấy danh sách lịch hẹn của người dùng đó
$stmt = $conn->prepare("SELECT a.id, u.username AS customer_name, d.username AS doctor_name, p.name AS pet_name, a.schedule_time, a.status, a.reason
FROM appointments a
JOIN users u ON a.customer_id = u.id
JOIN users d ON a.doctor_id = d.id
JOIN pets p ON a.pet_id = p.id
WHERE a.customer_id = ?
ORDER BY a.schedule_time DESC");

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Danh sách lịch hẹn</h2>
<a href="book_appointment.php">+ Đặt lịch mới</a>
<table border="1" cellpadding="5">
    <tr>
        <th>Thú cưng</th>
        <th>Khách hàng</th>
        <th>Bác sĩ</th>
        <th>Thời gian</th>
        <th>Tình trạng</th>
        <th>Lý do</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['pet_name']) ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
            <td><?= $row['schedule_time'] ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= nl2br(htmlspecialchars($row['reason'])) ?></td>
            <td>
                <?php
                if ($row['status'] === 'Đã khám') {
                    $stmtPay = $conn->prepare("SELECT status FROM payments WHERE appointment_id = ?");
                    $stmtPay->bind_param("i", $row['id']);
                    $stmtPay->execute();
                    $resultPay = $stmtPay->get_result();
                    $payment = $resultPay->fetch_assoc();

                    if ($payment && $payment['status'] === 'Chưa thanh toán') {
                        echo "<span style='color:red; font-weight:bold'>Cần thanh toán! <a href=\"pay_now.php?appointment_id={$row['id']}\">Thanh toán</a></span>";
                    } else {
                        echo "Đã thanh toán";
                    }
                } elseif ($row['status'] === 'Đã hủy') {
                    echo "<a href='#' onclick='deleteAppointment({$row['id']})' style='color:red'>Xoá</a>";
                } else {
                    ?>
                    <a href="#" onclick="return confirmCancel(<?= $row['id'] ?>)">Huỷ</a> |
                    <a href="#" onclick="event.preventDefault(); checkIn(<?= $row['id'] ?>)">Check-in</a>
                    <?php
                }
                ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<script>
function confirmCancel(appointmentId) {
    fetch("status_check.php", {
        method: "POST",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: "id=" + appointmentId
    })
    .then(res => res.json())
    .then(data => {
        if (data.allowed) {
            if (confirm("Bạn có chắc chắn muốn huỷ lịch hẹn này không?")) {
                window.location.href = "cancel_appointment.php?id=" + appointmentId;
            }
        } else {
            alert(data.message);
        }
    });
}

function checkIn(appointmentId) {
    fetch("status_check.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: "id=" + appointmentId
    })
    .then(res => res.json())
    .then(data => {
        if (data.allowed) {
            fetch("checkin_appointment.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: "id=" + appointmentId
            })
            .then(res => res.text())
            .then(response => {
                alert("Check-in thành công!");
                location.reload();
            });
        } else {
            alert(data.message || "Chỉ có thể check-in khi trạng thái là 'Đã đặt'!");
        }
    });
}

function deleteAppointment(id) {
    if (confirm("Bạn có chắc chắn muốn xoá lịch hẹn này không?")) {
        fetch("delete_appointment.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: "id=" + id
        })
        .then(res => res.text())
        .then(response => {
            alert("Xoá thành công!");
            location.reload();
        })
        .catch(error => {
            alert("Đã xảy ra lỗi khi xoá.");
            console.error(error);
        });
    }
}
</script>
