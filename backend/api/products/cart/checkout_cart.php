<?php
session_start();
require_once '../../../../includes/db.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false, 'error'=>'Bạn phải đăng nhập!']); exit;
}
$user_id = intval($_SESSION['user_id']);

// 1. Lấy cart hiện tại của user
$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows == 0) {
    echo json_encode(['success'=>false, 'error'=>'Giỏ hàng trống!']);
    exit;
}
$cart_id = $res->fetch_assoc()['id'];

// 2. Lấy toàn bộ sản phẩm trong cart
$stmt = $conn->prepare("
    SELECT ci.product_id, ci.quantity, p.price
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.cart_id = ?
");
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($cart_items)) {
    echo json_encode(['success'=>false, 'error'=>'Giỏ hàng trống!']);
    exit;
}

// 3. Tính tổng tiền
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// 4. Tạo order (status/payment_status có thể là "Chờ thanh toán" nếu chưa thực sự trả QR, sửa lại nếu muốn)
$stmt = $conn->prepare("
    INSERT INTO orders (customer_id, total_amount, status, payment_status, created_at)
    VALUES (?, ?, 'Đã thanh toán', 'Đã thanh toán', NOW())
");
$stmt->bind_param("id", $user_id, $total);
if (!$stmt->execute()) {
    echo json_encode(['success'=>false, 'error'=>'Không tạo được đơn hàng!']);
    exit;
}
$order_id = $conn->insert_id;

// 5. Tạo order_items cho từng sản phẩm (nếu chưa có thì cần tạo bảng order_items)
$stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($cart_items as $item) {
    $stmt_item->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmt_item->execute();
}

// 6. Xoá cart_items và cart (giỏ hàng tạm)
$conn->query("DELETE FROM cart_items WHERE cart_id = $cart_id");
$conn->query("DELETE FROM carts WHERE id = $cart_id");

echo json_encode(['success'=>true, 'msg'=>'Thanh toán thành công!', 'order_id'=>$order_id]);
?>
