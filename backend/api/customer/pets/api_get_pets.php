<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../../../includes/db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Unauthorized']);
    http_response_code(401);
    exit;
}

// Lấy user id từ username trong session
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(['error' => 'Không tìm thấy user']);
    exit;
}

$user_id = $user['id'];

// Lấy thú cưng của đúng user đang đăng nhập
$sql = "SELECT pets.*, users.username FROM pets 
        INNER JOIN users ON pets.owner_id = users.id
        WHERE pets.owner_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$pets = [];
while ($row = $result->fetch_assoc()) {
    $pets[] = $row;
}

echo json_encode($pets);
?>
