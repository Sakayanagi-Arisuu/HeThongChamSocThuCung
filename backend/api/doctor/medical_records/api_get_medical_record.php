<?php
require_once "../../../../includes/db.php";
header('Content-Type: application/json');
$id = intval($_GET['id'] ?? 0);
if (!$id) { echo json_encode(['success'=>false, 'error'=>'Thiếu id']); exit; }

$stmt = $conn->prepare("
    SELECT m.diagnosis, m.treatment, m.notes, a.fee
    FROM medical_records m
    JOIN appointments a ON m.appointment_id = a.id
    WHERE m.appointment_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($rec = $res->fetch_assoc()) {
    echo json_encode(['success'=>true, 'record'=>$rec]);
} else {
    echo json_encode(['success'=>false, 'error'=>'Không tìm thấy hồ sơ!']);
}
?>
