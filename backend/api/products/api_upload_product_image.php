<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success'=>false, 'error'=>'Không có quyền!']); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['image'])) {
    echo json_encode(['success'=>false, 'error'=>'Dữ liệu không hợp lệ!']); exit;
}
$uploadDir = '../../../uploads/products/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$file = $_FILES['image'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg','jpeg','png','gif'];
if (!in_array($ext, $allowed)) {
    echo json_encode(['success'=>false, 'error'=>'Định dạng ảnh không hợp lệ!']); exit;
}
$newName = 'product_' . time() . rand(10,99) . '.' . $ext;
$path = $uploadDir . $newName;
if (move_uploaded_file($file['tmp_name'], $path)) {
    $webUrl = '/HeThongChamSocThuCung/uploads/products/' . $newName;
    echo json_encode(['success'=>true, 'url'=>$webUrl]);
} else {
    echo json_encode(['success'=>false, 'error'=>'Upload thất bại!']);
}
?>
