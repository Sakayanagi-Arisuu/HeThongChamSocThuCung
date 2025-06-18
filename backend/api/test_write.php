<?php
$path = "D:/Xampp/htdocs/HeThongChamSocThuCung/uploads/avatars/test.txt";
$result = file_put_contents($path, "Hello test!");
if ($result === false) {
    echo "❌ Không ghi được file";
} else {
    echo "✅ Ghi file thành công!";
}
?>
