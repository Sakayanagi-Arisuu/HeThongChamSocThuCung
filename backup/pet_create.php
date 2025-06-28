<?php
require_once '../../../../includes/db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../../../api/auth/login.php');
    exit;
}

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

    $stmt = $conn->prepare("INSERT INTO pets (owner_id, name, species, breed, birth_date, gender, weight, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssds", $owner_id, $name, $species, $breed, $birth_date, $gender, $weight, $notes);

    if ($stmt->execute()) {
        header('Location: pet_index.php');
    } else {
        echo "Lỗi thêm thú cưng: " . $stmt->error;
    }
    $stmt->close();
}
?>
<h2>Thêm thú cưng</h2>
<form method="POST">
    Chủ: <select name="owner_id">
        <?php while ($user = $users->fetch_assoc()): ?>
            <option value="<?= $user['id'] ?>"><?= $user['username'] ?></option>
        <?php endwhile; ?>
    </select><br>
    Tên: <input name="name"><br>
    Loài: <input name="species"><br>
    Giống: <input name="breed"><br>
    Ngày sinh: <input type="date" name="birth_date"><br>
    Giới tính: <select name="gender"><option>Đực</option><option>Cái</option></select><br>
    Cân nặng: <input type="number" step="0.1" name="weight"><br>
    Ghi chú: <textarea name="notes"></textarea><br>
    <button type="submit">Thêm</button>
</form>
