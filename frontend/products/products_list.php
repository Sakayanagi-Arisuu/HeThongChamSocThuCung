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

<!-- <link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/products/products_list.css"> -->

<!--  CSS ko lỗi-->
<style>
.products-page #products-catalog {
    margin: 40px auto;
    max-width: 1200px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    justify-items: center;
}
.product-card {
    width: 250px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 3px 16px #0001;
    overflow: hidden;
    border: 1.5px solid #e1ecf1;
    display: flex;
    flex-direction: column;
    transition: transform 0.18s, box-shadow 0.18s;
    padding-bottom: 16px;
    min-height: 370px;
}
.product-card img {
    width: 100%;
    height: 170px;
    object-fit: cover;
    background: #f7f7f7;
}
.product-card-body {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    padding: 18px 16px 0 16px;
}
.product-title {
    font-size: 18px;
    font-weight: bold;
    color: #1976d2;
    margin-bottom: 4px;
}
.product-desc {
    font-size: 15px;
    color: #444;
    flex: 1;
    margin-bottom: 8px;
}
.product-category {
    font-size: 13px;
    color: #999;
    margin-bottom: 6px;
}
.product-price {
    font-size: 17px;
    color: #e53935;
    font-weight: bold;
    margin-bottom: 12px;
}
.btn-cart {
    background: #1976d2;
    color: #fff;
    border-radius: 7px;
    padding: 10px 0;
    border: none;
    font-weight: 600;
    font-size: 15px;
    width: 100%;
    cursor: pointer;
    margin-top: auto;
    transition: background .16s;
}
.btn-cart:hover {
    background: #1565c0;
}

</style>

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
