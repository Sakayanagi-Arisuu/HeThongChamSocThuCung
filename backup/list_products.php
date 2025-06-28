<?php
session_start();
require_once "../../../includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập!";
    exit;
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

echo "<table border='1'>
<tr>
    <th>ID</th>
    <th>Tên</th>
    <th>Giá</th>
    <th>Số lượng</th>
    <th>Phân loại</th>
    <th>Ảnh</th>
    <th>Thao tác</th>
</tr>";
while($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>".$row['id']."</td>
        <td>".$row['name']."</td>
        <td>".$row['price']."</td>
        <td>".$row['stock']."</td>
        <td>".$row['category']."</td>
        <td><img src='".$row['image']."' width='50'></td>
        <td>
            <a href='update_product.php?id=".$row['id']."'>Sửa</a> | 
            <a href='delete_product.php?id=".$row['id']."' onclick='return confirm(\"Xác nhận xóa?\")'>Xóa</a>
        </td>
    </tr>";
}
echo "</table>";
?>
