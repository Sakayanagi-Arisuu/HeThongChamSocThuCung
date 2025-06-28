<?php
require_once '../../../../includes/db.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'Chưa đăng nhập!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy owner_id từ session username
    $username = $_SESSION['username'];
    $userStmt = $conn->prepare("SELECT id FROM users WHERE username=?");
    $userStmt->bind_param("s", $username);
    $userStmt->execute();
    $user = $userStmt->get_result()->fetch_assoc();
    $userStmt->close();
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'Không tìm thấy user']);
        exit;
    }
    $owner_id = $user['id'];

    $id = $_POST['id'];
    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $weight = $_POST['weight'];
    $notes = $_POST['notes'];

    // Đảm bảo chỉ user đang đăng nhập mới được update pet của mình
    $stmt = $conn->prepare("UPDATE pets SET name=?, species=?, breed=?, birth_date=?, gender=?, weight=?, notes=? WHERE id=? AND owner_id=?");
    $stmt->bind_param("sssssdssi", $name, $species, $breed, $birth_date, $gender, $weight, $notes, $id, $owner_id);

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
