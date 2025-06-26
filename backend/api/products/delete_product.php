<?php
session_start();
require_once "../../../includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập!";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Thiếu hoặc sai ID!";
    exit;
}

$id = (int)$_GET['id'];
$sql = "DELETE FROM products WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>
        alert('Xóa sản phẩm thành công!');
        window.location.href = 'index_product.php';
    </script>";
    exit;
} else {
    echo "Lỗi: " . $stmt->error;
}
$stmt->close();
?>
