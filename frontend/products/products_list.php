<?php
session_start();
$page_title = "Mua sản phẩm thú cưng";
include '../../includes/header.php';

// Kiểm tra session đăng nhập và vai trò
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
<style>
#products-catalog { margin: 40px auto; max-width: 1100px; display: flex; flex-wrap: wrap; gap: 28px;}
.product-card {
    width: 250px; background: #fff; border-radius: 14px; box-shadow: 0 3px 16px #0001; overflow: hidden;
    display: flex; flex-direction: column; margin-bottom: 16px; border: 1.5px solid #e1ecf1;
    transition: transform 0.18s, box-shadow 0.18s;
}
.product-card:hover { transform:translateY(-2px) scale(1.02); box-shadow: 0 8px 30px #0002;}
.product-card img { width: 100%; height: 180px; object-fit: cover; }
.product-card-body { padding: 18px 18px 12px 18px; flex: 1;}
.product-title { font-weight: bold; font-size: 19px; color: #2196f3;}
.product-desc { color: #444; font-size: 15px; margin: 8px 0 12px 0; min-height: 40px;}
.product-price { color: #e53935; font-size: 18px; font-weight: bold; margin-bottom: 10px;}
.product-category { font-size: 13px; color: #999;}
.btn-cart {
    display:block; width:100%; padding:10px; background:#1976d2; color:#fff;
    border-radius:8px; font-weight:600; font-size:16px; border:none; cursor:pointer;
    margin-top:7px; transition:background .16s;
}
.btn-cart:hover { background: #1565c0; }
</style>

<div id="products-catalog"></div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/HeThongChamSocThuCung/backend/api/products/api_get_products.php')
    .then(res => res.json())
    .then(data => {
        let html = '';
        data.forEach(p=>{
            html += `
            <div class="product-card">
                <img src="${p.image||'/HeThongChamSocThuCung/images/default-product.jpg'}" alt="SP">
                <div class="product-card-body">
                    <div class="product-title">${p.name}</div>
                    <div class="product-desc">${p.description||''}</div>
                    <div class="product-category">${p.category||''}</div>
                    <div class="product-price">${Number(p.price).toLocaleString()} đ</div>
                    <button class="btn-cart" onclick="addToCart(${p.id})">Thêm vào giỏ</button>
                </div>
            </div>`;
        });
        document.getElementById('products-catalog').innerHTML = html || "<p>Không có sản phẩm.</p>";
    });
});

function addToCart(product_id) {
    fetch('/HeThongChamSocThuCung/backend/api/products/cart/add_to_cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'product_id=' + product_id
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Đã thêm vào giỏ!');
        } else {
            alert(data.error || 'Lỗi!');
        }
    });
}
</script>
<?php
include '../../includes/footer.php';
?>
