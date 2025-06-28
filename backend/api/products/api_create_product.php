<?php
session_start();
require_once "../../../includes/db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success'=>false, 'error'=>"Bạn không có quyền truy cập!"]); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false, 'error'=>'Sai method']); exit;
}

$name = trim($_POST['name'] ?? '');
$description = $_POST['description'] ?? '';
$price = floatval($_POST['price'] ?? 0);
$stock = intval($_POST['stock'] ?? 0);
$image = trim($_POST['image'] ?? '');
$category = trim($_POST['category'] ?? '');

$stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image, category) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdiss", $name, $description, $price, $stock, $image, $category);

if ($stmt->execute()) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false, 'error'=>$stmt->error]);
}
?>
