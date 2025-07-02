<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$page_title = "Pet Care Services";
include '../../includes/header.php';
include '../../includes/navbar_customer.php';
?>
<!-- css -->
<link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/customer/my_pets.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="main-content">
    <div style="flex: 2;">
        <div id="pets-table"></div>
    </div>
    <div class="sidebar-right">
        <?php include '../../includes/category.php'; ?>
    </div>
</div>

<!-- Popup form thêm/sửa thú cưng -->
<div id="pet-form-modal">
  <div class="pet-form-content">
    <button onclick="closePetForm()" class="close-btn">&times;</button>
    <h3 id="pet-form-title"></h3>
    <form id="pet-form">
      <input type="hidden" name="id" />
      <label>Tên: <input name="name" required></label>
      <label>Loài: <input name="species"></label>
      <label>Giống: <input name="breed"></label>
      <label>Ngày sinh: <input type="date" name="birth_date"></label>
      <label>Giới tính:
        <select name="gender">
          <option>Đực</option>
          <option>Cái</option>
        </select>
      </label>
      <label>Cân nặng(kg): <input type="number" step="0.1" name="weight"></label>
      <label>Ghi chú: <textarea name="notes"></textarea></label>
      <button type="submit" id="pet-form-submit">Lưu</button>
    </form>
  </div>
</div>
<!-- script -->
<script src="/HeThongChamSocThuCung/assets/js/customer/my_pets.js"></script>
<?php

include '../../includes/footer.php';
?>