<?php
session_start();
require_once "../../../includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];
    $category = $_POST['category'];

    $sql = "INSERT INTO products (name, description, price, stock, image, category) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisd", $name, $description, $price, $stock, $image, $category);

    if ($stmt->execute()) {
        echo "<script>
            alert('Thêm sản phẩm thành công!');
            window.location.href = 'index_product.php';
        </script>";
        exit;
    } else {
        echo "Lỗi: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!-- Form thêm sản phẩm -->
<form method="post">
    Tên sản phẩm: <input type="text" name="name"><br>
    Mô tả: <textarea name="description"></textarea><br>
    Giá: <input type="number" name="price" step="0.01"><br>
    Số lượng: <input type="number" name="stock"><br>
    Ảnh: <input type="text" name="image"><br>
    Phân loại: <input type="text" name="category"><br>
    <input type="submit" value="Thêm sản phẩm">
</form>
