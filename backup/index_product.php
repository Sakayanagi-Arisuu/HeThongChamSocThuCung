<?php
require_once "../../../includes/db.php";
session_start();

if (!isset($_SESSION['username'])) {
    echo "Truy cập bị từ chối.";
    exit;
}

// Xử lý xóa sản phẩm khi có ?delete=ID
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Chuyển hướng với flag báo xoá thành công
    echo "<script>
        alert('Xóa sản phẩm thành công!');
        window.location.href = 'index_product.php';
    </script>";
    exit();
}

// Xử lý thêm hoặc sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];
    $category = $_POST['category'];

    if (isset($_POST['update_id']) && $_POST['update_id']) {
        // Sửa
        $id = $_POST['update_id'];
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image=?, category=? WHERE id=?");
        $stmt->bind_param("ssdisdi", $name, $description, $price, $stock, $image, $category, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Thêm mới
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image, category) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdisd", $name, $description, $price, $stock, $image, $category);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>
        alert('Lưu sản phẩm thành công!');
        window.location.href = 'index_product.php';
    </script>";
    exit();
}

// Nếu bấm "Sửa" thì lấy thông tin sản phẩm lên form
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM products WHERE id=$id");
    $edit = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý sản phẩm</title>
    <style>
        table {border-collapse: collapse;}
        th, td {padding: 5px 10px; border: 1px solid #ccc;}
        img {max-width: 40px;}
        .form-section {margin-bottom: 30px; border: 1px solid #eee; padding: 10px;}
    </style>
    <script>
    function confirmDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
            window.location.href = "index_product.php?delete=" + id;
        }
    }
    </script>
</head>
<body>
    <h2>QUẢN LÝ SẢN PHẨM</h2>
    <a href="create_product.php">Thêm sản phẩm</a>
    <h3>Danh sách sản phẩm</h3>
    <table>
        <tr>
            <th>ID</th><th>Tên</th><th>Giá</th><th>Số lượng</th><th>Phân loại</th><th>Ảnh</th><th>Thao tác</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['stock']}</td>
                <td>{$row['category']}</td>
                <td>".($row['image'] ? "<img src='{$row['image']}' />" : "")."</td>
                <td>
                    <a href='update_product.php?id={$row['id']}'>Sửa</a> | 
                    <a href='#' onclick='confirmDelete({$row['id']})'>Xóa</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>
