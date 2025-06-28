<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$page_title = "Pet Care Services";
include '../../includes/header.php'; // header + navbar
include '../../includes/navbar_customer.php';
?>

<div class="main-content">
    <div class="left-content">
        <div class="banner">
            <img src="../../images/anh2.jpg" alt="Banner Pet Care" style="width:100%; border-radius:12px;">
        </div>
    </div>
    <?php include '../../includes/category.php'; // sidebar phải ?>
</div>

<?php include '../../includes/footer.php'; ?>
