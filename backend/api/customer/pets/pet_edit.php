<?php
require_once '../../../../includes/db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../../../auth/login.php');
    exit;
}

$id = $_GET['id'];
$pets = $conn->query("SELECT * FROM pets WHERE id = $id")->fetch_assoc();
$users = $conn->query("SELECT id, username FROM users");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_id = $_POST['owner_id'];
    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $weight = $_POST['weight'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("UPDATE pets SET owner_id=?, name=?, species=?, breed=?, birth_date=?, gender=?, weight=?, notes=? WHERE id=?");
    $stmt->bind_param("isssssisi", $owner_id, $name, $species, $breed, $birth_date, $gender, $weight, $notes, $id);

    if ($stmt->execute()) {
        header('Location: pet_index.php');
    } else {
        echo "Lỗi cập nhật: " . $stmt->error;
    }
    $stmt->close();
}
?>
<h2>Sửa thú cưng</h2>
<form method="POST">
    Chủ: <select name="owner_id">
        <?php while ($user = $users->fetch_assoc()): ?>
            <option value="<?= $user['id'] ?>" <?= $user['id'] == $pets['owner_id'] ? 'selected' : '' ?>><?= $user['username'] ?></option>
        <?php endwhile; ?>
    </select><br>
    Tên: <input name="name" value="<?= $pets['name'] ?>"><br>
    Loài: <input name="species" value="<?= $pets['species'] ?>"><br>
    Giống: <input name="breed" value="<?= $pets['breed'] ?>"><br>
    Ngày sinh: <input type="date" name="birth_date" value="<?= $pets['birth_date'] ?>"><br>
    Giới tính: <select name="gender">
        <option <?= $pets['gender'] == 'Đực' ? 'selected' : '' ?>>Đực</option>
        <option <?= $pets['gender'] == 'Cái' ? 'selected' : '' ?>>Cái</option>
    </select><br>
    Cân nặng: <input type="number" step="0.1" name="weight" value="<?= $pets['weight'] ?>"><br>
    Ghi chú: <textarea name="notes"><?= $pets['notes'] ?></textarea><br>
    <button type="submit">Cập nhật</button>
</form>