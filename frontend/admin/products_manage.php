<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$page_title = "Quản lý sản phẩm - Pet Care Services";
include '../../includes/header.php';
include '../../includes/navbar_admin.php';
?>
<link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/admin/products_manage.css">

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="main-content">
    <div style="flex: 2;">
        <div id="products-table"></div>
    </div>
    <div class="sidebar-right">
        <?php include '../../includes/category.php'; ?>
    </div>
</div>

<!-- Popup form thêm/sửa sản phẩm -->
<div id="product-form-modal">
  <div class="product-form-content">
    <button onclick="closeProductForm()" class="close-btn">&times;</button>
    <h3 id="product-form-title"></h3>
    <form id="product-form" enctype="multipart/form-data">
      <input type="hidden" name="id" />
      <div class="form-row">
        <label>Tên sản phẩm:</label>
        <input name="name" required>
      </div>
      <div class="form-row">
        <label>Mô tả:</label>
        <textarea name="description" rows="2"></textarea>
      </div>
      <div class="form-row">
        <label>Giá:</label>
        <input type="number" name="price" step="0.01" required>
      </div>
      <div class="form-row">
        <label>Tồn kho:</label>
        <input type="number" name="stock" min="0" required>
      </div>
      <div class="form-row">
        <label>Ảnh sản phẩm:</label>
        <input type="file" name="imageFile" accept="image/*" onchange="previewProductImage(this)">
      </div>
      <img id="productImagePreview">
      <div class="form-row">
        <label>Phân loại:</label>
        <select name="category" required>
            <option value="">-- Chọn loại sản phẩm --</option>
            <option value="Thức ăn cho chó">Thức ăn cho chó</option>
            <option value="Thức ăn cho mèo">Thức ăn cho mèo</option>
            <option value="Sản phẩm mới">Sản phẩm mới</option>
            <option value="Vật tư y tế">Vật tư y tế</option>
            <option value="Vật dụng cho chó mèo">Vật dụng cho chó mèo</option>
        </select>
        </div>
      <button type="submit" id="product-form-submit">Lưu</button>
    </form>
  </div>
</div>


<script src="/HeThongChamSocThuCung/assets/js/admin/products_manage.js"></script>

