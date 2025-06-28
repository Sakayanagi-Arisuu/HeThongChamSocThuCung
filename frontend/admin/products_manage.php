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
<style>
#products-table {
    margin: 32px 0 0 0;
    width: 100%;
    max-width: 1000px;
}
.main-content {
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
}
.sidebar-right {
    min-width: 270px;
    margin-left: 32px;
}
#products-table table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,160,200,0.10), 0 1.5px 4px rgba(0,0,0,0.08);
    font-size: 16px;
    margin-top: 12px;
}
#products-table th, #products-table td {
    border: 1px solid #e0eaf1;
    padding: 10px 12px;
    text-align: center;
}
#products-table th {
    background: #29b6f6;
    color: #fff;
    font-weight: bold;
    letter-spacing: 0.5px;
}
#products-table tr:nth-child(even) {
    background: #f4fafd;
}
#products-table tr:hover {
    background: #e0f7fa;
}
#products-table h2 {
    color: #009fe3;
    font-size: 28px;
    margin-bottom: 10px;
    margin-top: 0;
}
#products-table .add-product-link {
    display: inline-block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #00bcd4;
    text-decoration: none;
    font-size: 18px;
    transition: color 0.15s;
}
#products-table .add-product-link:hover {
    color: #e64a19;
}
.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border: none;
    border-radius: 6px;
    padding: 5px 14px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.12s;
    margin: 0 2px;
}
.action-edit {
    background: #fff4e3;
    color: #ef6c00;
    border: 1.5px solid #ffd3a2;
}
.action-edit:hover {
    background: #ffe0b2;
    color: #d84315;
}
.action-delete {
    background: #ffe6e9;
    color: #d32f2f;
    border: 1.5px solid #ffbfc3;
}
.action-delete:hover {
    background: #ffcdd2;
    color: #b71c1c;
}
.material-icons {
    font-family: 'Material Icons';
    font-style: normal;
    font-weight: normal;
    font-size: 20px;
    line-height: 1;
    display: inline-block;
    direction: ltr;
    -webkit-font-feature-settings: 'liga';
    -webkit-font-smoothing: antialiased;
}
/* Popup layout sửa */
#product-form-modal {
    display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:999;
    background:rgba(0,0,0,0.15); align-items:center; justify-content:center;
}
#product-form-modal .product-form-content {
    background:#fff; border-radius:12px; box-shadow:0 2px 8px #0002;
    padding:28px 32px; width:410px; position:relative;
    animation: popupOpen .21s;
}
@keyframes popupOpen { from {transform:scale(.95);opacity:0;} to {transform:scale(1);opacity:1;} }
#product-form-modal h3 {margin-top:0;}
#product-form-modal .form-row {
    display: flex;
    align-items: flex-start;
    margin-bottom: 13px;
}
#product-form-modal .form-row label {
    min-width: 120px;
    margin-right: 10px;
    font-weight: 500;
    padding-top: 7px;
}
#product-form-modal .form-row input,
#product-form-modal .form-row textarea {
    flex: 1;
    border-radius: 5px;
    border: 1px solid #bbb;
    padding: 6px 10px;
    font-size: 15px;
    background: #fafdff;
}
#product-form-modal .form-row input[type="file"] {
    padding: 0;
    background: none;
}
#product-form-modal img#productImagePreview {
    max-width:90px; max-height:90px; display:none; margin:6px 0 10px 130px; border-radius:7px; border:1px solid #eee;
}
#product-form-modal button[type="submit"] {
    width: 100%; padding: 12px; border-radius: 7px; border: none;
    background: #29b6f6; color: #fff; font-weight: 600; font-size: 16px; cursor: pointer; margin-top: 4px;
}
#product-form-modal button[type="submit"]:hover { background: #1976d2; }
#product-form-modal .close-btn {
    position:absolute;right:14px;top:13px;background:none;border:none;font-size:26px;color:#999;cursor:pointer;
}
</style>
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
        <input name="category">
      </div>
      <button type="submit" id="product-form-submit">Lưu</button>
    </form>
  </div>
</div>

<script>
function openProductForm(mode, product = {}) {
    document.getElementById('product-form-modal').style.display = 'flex';
    document.getElementById('product-form-title').innerText = (mode === 'add') ? 'Thêm sản phẩm' : 'Sửa sản phẩm';
    document.getElementById('product-form-submit').innerText = (mode === 'add') ? 'Thêm' : 'Cập nhật';

    // Reset form và preview ảnh
    const form = document.getElementById('product-form');
    form.reset();
    form.mode = mode;

    // Điền dữ liệu nếu là edit
    form.elements['id'].value = product.id || '';
    form.elements['name'].value = product.name || '';
    form.elements['description'].value = product.description || '';
    form.elements['price'].value = product.price || '';
    form.elements['stock'].value = product.stock || '';
    form.elements['category'].value = product.category || '';

    // Hiện preview ảnh nếu sửa
    let imgPreview = document.getElementById('productImagePreview');
    if (product.image) {
        imgPreview.src = product.image;
        imgPreview.style.display = 'block';
    } else {
        imgPreview.style.display = 'none';
        imgPreview.src = '';
    }
}
function closeProductForm() {
    document.getElementById('product-form-modal').style.display = 'none';
}

// Preview ảnh sản phẩm khi chọn file
function previewProductImage(input) {
    const img = document.getElementById('productImagePreview');
    if (input.files && input.files[0]) {
        img.style.display = 'block';
        img.src = URL.createObjectURL(input.files[0]);
    } else {
        img.style.display = 'none';
        img.src = '';
    }
}

// Xử lý submit form AJAX với upload ảnh
document.getElementById('product-form').onsubmit = function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    // Kiểm tra có chọn ảnh mới không?
    let imageInput = form.elements['imageFile'];
    if (imageInput && imageInput.files && imageInput.files[0]) {
        // Upload ảnh trước
        let uploadData = new FormData();
        uploadData.append('image', imageInput.files[0]);
        fetch('/HeThongChamSocThuCung/backend/api/products/api_upload_product_image.php', {
            method: 'POST',
            body: uploadData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.url) {
                formData.append('image', data.url);
                submitProductFormAjax(form, formData);
            } else {
                alert(data.error || 'Upload ảnh thất bại!');
            }
        });
    } else {
        submitProductFormAjax(form, formData); // Không chọn ảnh mới
    }
};

function submitProductFormAjax(form, formData) {
    let url = '/HeThongChamSocThuCung/backend/api/products/api_create_product.php';
    if (form.mode === 'edit') {
        url = '/HeThongChamSocThuCung/backend/api/products/api_edit_product.php';
    }
    fetch(url, {
        method: 'POST',
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            closeProductForm();
            loadProductsTable();
        } else {
            alert(data.error || 'Có lỗi xảy ra!');
        }
    });
}

// Xoá sản phẩm
function deleteProduct(id) {
    if (!confirm('Xoá sản phẩm này?')) return;
    fetch('/HeThongChamSocThuCung/backend/api/products/api_delete_product.php', {
        method:'POST', body: new URLSearchParams({id:id}),
    })
    .then(res=>res.json())
    .then(data=>{
        if (data.success) loadProductsTable();
        else alert(data.error||'Lỗi');
    });
}

// Tải danh sách sản phẩm
function loadProductsTable() {
    fetch('/HeThongChamSocThuCung/backend/api/products/api_get_products.php')
    .then(res=>res.json())
    .then(data=>{
        let html = `
            <h2>Danh sách sản phẩm</h2>
            <a href="javascript:void(0)" onclick="openProductForm('add')" class="add-product-link">+ Thêm sản phẩm</a>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Mô tả</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Ảnh</th>
                    <th>Phân loại</th>
                    <th>Hành động</th>
                </tr>
        `;
        if (!data || data.length === 0) {
            html += `<tr><td colspan="8" style="color:#888;">Chưa có sản phẩm nào!</td></tr>`;
        } else {
            data.forEach(product=>{
                html += `
                <tr>
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.description||''}</td>
                    <td>${product.price}</td>
                    <td>${product.stock}</td>
                    <td>${product.image ? `<img src="${product.image}" style="max-width:60px;max-height:60px;border-radius:7px;">` : ''}</td>
                    <td>${product.category||''}</td>
                    <td>
                        <button type="button" class="action-btn action-edit" title="Sửa"
                          onclick='openProductForm("edit", ${JSON.stringify(product)})'>
                          <span class="material-icons">edit</span> Sửa
                        </button>
                        <button type="button" class="action-btn action-delete" title="Xoá"
                          onclick="deleteProduct(${product.id})">
                          <span class="material-icons">delete</span> Xoá
                        </button>
                    </td>
                </tr>
                `;
            });
        }
        html += '</table>';
        document.getElementById('products-table').innerHTML = html;
    })
    .catch(()=>{
        document.getElementById('products-table').innerHTML = "<p>Lỗi kết nối server!</p>";
    });
}

document.addEventListener('DOMContentLoaded', function() {
    loadProductsTable();
});
</script>
