<?php
require_once '../../../../includes/db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}

$pet_id = (int)$_GET['id'];

// Chỉ cho phép xóa nếu KHÔNG có lịch nào khác "Đã huỷ"
$sql = "SELECT COUNT(*) as cnt FROM appointments WHERE pet_id = ? AND status != 'Đã hủy'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result['cnt'] > 0) {
    // Có lịch chưa hủy -> không xóa
    echo "<script>
        alert('Không thể xóa vì thú cưng đang có lịch khám chưa hủy!');
        window.location.href='/HeThongChamSocThuCung/frontend/customer/my_pets.php';
    </script>";
    exit;
}

// Được phép xóa (vì chỉ còn lịch "Đã huỷ" hoặc không còn lịch nào)
$conn->query("DELETE FROM pets WHERE id = $pet_id");

header("Location: /HeThongChamSocThuCung/frontend/customer/my_pets.php");
exit;
?>
