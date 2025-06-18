<?php
require_once "../../../../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Xoá lịch hẹn
    $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Đã xoá thành công.";
    } else {
        echo "Không tìm thấy lịch hẹn.";
    }
} else {
    echo "Yêu cầu không hợp lệ.";
}
