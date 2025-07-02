<?php
session_start();
$page_title = "Mua sản phẩm thú cưng";
include '../../includes/header.php';

if (!isset($_SESSION['role'])) {
    include '../../includes/navbar_guest.php';
} else {
    switch ($_SESSION['role']) {
        case 'customer':
            include '../../includes/navbar_customer.php';
            break;
        case 'doctor':
            include '../../includes/navbar_doctor.php';
            break;
        case 'admin':
            include '../../includes/navbar_admin.php';
            break;
        default:
            include '../../includes/navbar_guest.php';
            break;
    }
}
?>
<link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/products/products_list.css">

<div class="products-page">
    <div class="products-toolbar" style="margin: 30px 0 10px 0; display:flex; gap:16px; align-items:center;">
        <input id="search-product" type="text" placeholder="🔍 Tìm kiếm sản phẩm..." 
            style="padding:12px 18px; border-radius:9px; border:2px solid #1976d2; width:370px; font-size:19px;"/>
        <select id="filter-category" style="padding:8px 10px; border-radius:7px; border:1.5px solid #e1ecf1;">
            <option value="">Tất cả loại sản phẩm</option>
            <option value="Thức ăn cho chó">Thức ăn cho chó</option>
            <option value="Thức ăn cho mèo">Thức ăn cho mèo</option>
            <option value="Sản phẩm mới">Sản phẩm mới</option>
            <option value="Vật tư y tế">Vật tư y tế</option>
            <option value="Vật dụng cho chó mèo">Vật dụng cho chó mèo</option>
        </select>
    </div>
    <div id="products-catalog"></div>
</div>

<script src="/HeThongChamSocThuCung/assets/js/products/products_list.js"></script>
<?php include '../../includes/footer.php'; ?>
