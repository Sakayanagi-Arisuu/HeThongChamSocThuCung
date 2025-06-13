<?php
require_once '../../../includes/db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../../../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Không tìm thấy ID thú cưng.";
    exit;
}

$id = $_GET['id'];

// JOIN để lấy cả tên chủ sở hữu
$sql = "SELECT pets.*, users.username AS owner_name FROM pets INNER JOIN users ON pets.owner_id = users.id WHERE pets.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pet = $result->fetch_assoc();

if (!$pet) {
    echo "Thú cưng không tồn tại.";
    exit;
}
?>

<h2>Chi tiết thú cưng</h2>
<ul>
    <li><strong>Chủ:</strong> <?= htmlspecialchars($pet['owner_name']) ?></li>
    <li><strong>Tên:</strong> <?= htmlspecialchars($pet['name']) ?></li>
    <li><strong>Loài:</strong> <?= htmlspecialchars($pet['species']) ?></li>
    <li><strong>Giống:</strong> <?= htmlspecialchars($pet['breed']) ?></li>
    <li><strong>Ngày sinh:</strong> <?= htmlspecialchars($pet['birth_date']) ?></li>
    <li><strong>Giới tính:</strong> <?= htmlspecialchars($pet['gender']) ?></li>
    <li><strong>Cân nặng:</strong> <?= htmlspecialchars($pet['weight']) ?> kg</li>
    <li><strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($pet['notes'])) ?></li>
</ul>

<a href="pets.php">Quay lại danh sách</a>
