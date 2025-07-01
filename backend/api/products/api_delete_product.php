<?php
session_start();
require_once "../../../includes/db.php";
header('Content-Type: application/json');

// Đảm bảo MySQLi sẽ ném exception (quan trọng!)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success'=>false, 'error'=>"Bạn không có quyền truy cập!"]); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false, 'error'=>'Sai method']); exit;
}

$id = intval($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Thiếu ID sản phẩm']); exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success'=>true]);
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1451 || stripos($e->getMessage(), 'foreign key constraint fails') !== false) {
        echo json_encode([
            'success' => false,
            'error' => 'Sản phẩm có lịch sử mua bán liên quan không thể xoá!'
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Lỗi: ' . $e->getMessage()]);
    }
}
?>
