<?php
session_start();
require_once "../../../includes/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Bạn không có quyền truy cập!";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Thiếu hoặc sai ID sản phẩm!";
    exit;
}
$id = (int)$_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];
    $category = $_POST['category'];

    $sql = "UPDATE products SET name=?, description=?, price=?, stock=?, image=?, category=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisdi", $name, $description, $price, $stock, $image, $category, $id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Cập nhật sản phẩm thành công!');
            window.location.href = 'index_product.php';
        </script>";
        exit;
    } else {
        echo "Lỗi: " . $stmt->error;
    }
    $stmt->close();
}

// Lấy dữ liệu sản phẩm
$sql = "SELECT * FROM products WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    echo "Không tìm thấy sản phẩm!";
    exit;
}
?>

<!-- Form cập nhật sản phẩm -->
<form method="post">
    Tên sản phẩm: <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"><br>
    Mô tả: <textarea name="description"><?php echo htmlspecialchars($row['description']); ?></textarea><br>
    Giá: <input type="number" name="price" step="0.01" value="<?php echo $row['price']; ?>"><br>
    Số lượng: <input type="number" name="stock" value="<?php echo $row['stock']; ?>"><br>
    Ảnh: <input type="text" name="image" value="<?php echo htmlspecialchars($row['image']); ?>"><br>
    Phân loại: <input type="text" name="category" value="<?php echo htmlspecialchars($row['category']); ?>"><br>
    <input type="submit" value="Cập nhật">
</form>
