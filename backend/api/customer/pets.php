<?php
require_once '../../../includes/db.php';
session_start();

// Kiểm tra nếu không đăng nhập hoặc không phải admin thì chặn
// if (!isset($_SESSION['username']))  {
//     header('Location: ../../../auth/login.php');
//     exit;
// }

$sql = "SELECT pets.*, users.username FROM pets INNER JOIN users ON pets.owner_id = users.id";
$result = $conn->query($sql);
?>
<h2>Danh sách thú cưng</h2>
<a href="pet_create.php">+ Thêm thú cưng</a>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Loài</th>
        <th>Giống</th>
        <th>Ngày sinh</th>
        <th>Giới tính</th>
        <th>Cân nặng</th>
        <th>Ghi chú</th>
        <th>Chủ</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['species'] ?></td>
        <td><?= $row['breed'] ?></td>
        <td><?= $row['birth_date'] ?></td>
        <td><?= $row['gender'] ?></td>
        <td><?= $row['weight'] ?></td>
        <td><?= $row['notes'] ?></td>
        <td><?= $row['username'] ?></td>
        <td>
            <a href="pet_details.php?id=<?= $row['id'] ?>">Xem chi tiết</a> |
            <a href="pet_edit.php?id=<?= $row['id'] ?>">Sửa</a> |
            <a href="pet_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Xóa thú cưng này?')">Xóa</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
