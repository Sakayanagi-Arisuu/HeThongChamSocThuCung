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