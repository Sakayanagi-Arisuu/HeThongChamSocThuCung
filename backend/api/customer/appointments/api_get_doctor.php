<?php
require_once '../../../../includes/db.php';
header('Content-Type: application/json');
$doctors = $conn->query("SELECT id, username FROM users WHERE role = 'bác sĩ'");
$data = [];
while ($d = $doctors->fetch_assoc()) $data[] = $d;
echo json_encode($data);
?>
