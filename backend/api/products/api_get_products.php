<?php
require_once "../../../includes/db.php";
header('Content-Type: application/json');

// Lấy tham số GET nếu có
$q = isset($_GET['q']) ? $_GET['q'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Hàm loại bỏ dấu tiếng Việt
function removeVietnameseTones($str) {
    $patterns = [
        '/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/u' => 'a',
        '/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/u' => 'e',
        '/(ì|í|ị|ỉ|ĩ)/u' => 'i',
        '/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/u' => 'o',
        '/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/u' => 'u',
        '/(ỳ|ý|ỵ|ỷ|ỹ)/u' => 'y',
        '/(đ)/u' => 'd',
        '/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/u' => 'A',
        '/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/u' => 'E',
        '/(Ì|Í|Ị|Ỉ|Ĩ)/u' => 'I',
        '/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/u' => 'O',
        '/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/u' => 'U',
        '/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/u' => 'Y',
        '/(Đ)/u' => 'D'
    ];
    return preg_replace(array_keys($patterns), array_values($patterns), $str);
}

// Xây dựng truy vấn SQL động
$sql = "SELECT * FROM products WHERE 1";
if ($category !== '') {
    $sql .= " AND category = '" . $conn->real_escape_string($category) . "'";
}
$sql .= " ORDER BY created_at DESC";

// Truy vấn dữ liệu
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    // Lọc theo tên ở phía PHP nếu có từ khóa tìm kiếm
    if ($q !== '') {
        $name_norm = strtolower(removeVietnameseTones($row['name']));
        $q_norm = strtolower(removeVietnameseTones($q));
        if (strpos($name_norm, $q_norm) === false) {
            continue; // không khớp, bỏ qua sản phẩm này
        }
    }
    $products[] = $row;
}

echo json_encode($products);
?>
