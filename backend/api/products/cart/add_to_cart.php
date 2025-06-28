<?php
session_start();
require_once '../../../../includes/db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$product_id = intval($_POST['product_id'] ?? 0);

if (!$user_id) {
    echo json_encode(['success'=>false, 'error'=>'Bạn phải đăng nhập!']); exit;
}

// 1. Lấy hoặc tạo cart cho user
$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $cart_id = $res->fetch_assoc()['id'];
} else {
    $stmt2 = $conn->prepare("INSERT INTO carts(user_id) VALUES(?)");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $cart_id = $conn->insert_id;
}

// 2. Thêm hoặc tăng số lượng trong cart_items
$stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE cart_id=? AND product_id=?");
$stmt->bind_param("ii", $cart_id, $product_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $new_quantity = $row['quantity'] + 1;
    $stmt2 = $conn->prepare("UPDATE cart_items SET quantity=? WHERE id=?");
    $stmt2->bind_param("ii", $new_quantity, $row['id']);
    $stmt2->execute();
} else {
    $stmt2 = $conn->prepare("INSERT INTO cart_items(cart_id, product_id, quantity) VALUES (?, ?, 1)");
    $stmt2->bind_param("ii", $cart_id, $product_id);
    $stmt2->execute();
}

echo json_encode(['success'=>true]);
