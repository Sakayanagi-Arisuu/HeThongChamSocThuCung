<?php
require_once "../../../includes/db.php";
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $rawPassword = $_POST['password'] ?? '';
    $fullName = trim($_POST['full_name'] ?? '');
    $contactInfo = trim($_POST['contact_info'] ?? '');

    if ($username === "" || $rawPassword === "" || $fullName === "" || $contactInfo === "") {
        echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin.']);
        exit;
    }

    $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

    // Kiểm tra username đã tồn tại chưa
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.']);
    } else {
        // Thêm role là customer
        $role = 'customer';
        $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, contact_info, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $hashedPassword, $fullName, $contactInfo, $role);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Đăng ký thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $stmt->error]);
        }
        $stmt->close();
    }

    $checkStmt->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ!']);
exit;
?>
