<?php
session_start();
require_once '../../../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}

$cart_item_id = intval($_POST['cart_item_id'] ?? 0);
$user_id = $_SESSION['user_id'];

// Lấy cart_id theo user
$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$cart_id = 0;
if ($res->num_rows > 0) {
    $cart_id = $res->fetch_assoc()['id'];
}

// Xoá sản phẩm khỏi giỏ hàng (chỉ nếu sản phẩm thuộc cart của user này)
if ($cart_id && $cart_item_id) {
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE id=? AND cart_id=?");
    $stmt->bind_param("ii", $cart_item_id, $cart_id);
    $stmt->execute();
}

// Quay lại trang giỏ hàng
header("Location: /HeThongChamSocThuCung/frontend/customer/cart.php");
exit;
?>
