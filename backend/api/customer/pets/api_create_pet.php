<?php
require_once '../../../../includes/db.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'Chưa đăng nhập!']);
    exit;
}

// Lấy id user từ session username
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
if (!$result) {
    echo json_encode(['success' => false, 'error' => 'Không tìm thấy tài khoản']);
    exit;
}
$owner_id = $result['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    $stmt->close();
    exit;
}

echo json_encode(['success' => false, 'error' => 'Phương thức không hợp lệ']);
exit;
