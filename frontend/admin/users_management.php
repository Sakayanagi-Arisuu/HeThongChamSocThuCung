<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$page_title = "Quản lý người dùng - Pet Care Services";
include '../../includes/header.php';
include '../../includes/navbar_admin.php';
?>
<link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/admin/users_management.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="main-content">
    <div style="flex: 2;">
        <div id="users-table"></div>
    </div>
    <div class="sidebar-right">
        <?php include '../../includes/category.php'; ?>
    </div>
</div>

<!-- Popup form thêm/sửa người dùng -->
<div id="user-form-modal">
  <div class="user-form-content">
    <button onclick="closeUserForm()" class="close-btn">&times;</button>
    <h3 id="user-form-title"></h3>
    <form id="user-form">
      <input type="hidden" name="id" />
      <label>Tên đăng nhập: <input name="username" required></label>
      <label>Họ tên: <input name="full_name"></label>
      <label>Vai trò:
        <select name="role" required>
          <option value="admin">Admin</option>
          <option value="doctor">Bác sĩ</option>
          <option value="customer">Khách hàng</option>
        </select>
      </label>
      <label>Mật khẩu mới: <input type="password" name="password" autocomplete="new-password"></label>
      <button type="submit" id="user-form-submit">Lưu</button>
    </form>
  </div>
</div>

<script src="/HeThongChamSocThuCung/assets/js/admin/users_management.js"></script>
