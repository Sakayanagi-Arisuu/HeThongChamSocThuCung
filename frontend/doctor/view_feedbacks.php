<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$page_title = "Đánh giá của khách hàng";
include '../../includes/header.php';
include '../../includes/navbar_doctor.php';
?>
<!-- css -->
<link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/doctor/view_feedbacks.css">

<div id="feedback-table"></div>
<!-- script -->
<script src="/HeThongChamSocThuCung/assets/js/doctor/view_feedbacks.js"></script>
<?php include '../../includes/footer.php'; ?>
