function renderProducts(data) {
    let html = '';
    data.forEach(p => {
        html += `
        <div class="product-card">
            <img src="${p.image || 'anh.jpg'}" alt="SP" />
            <div class="product-card-body">
                <div class="product-title">${p.name}</div>
                <div class="product-desc">${p.description}</div>
                <div class="product-category">${p.category}</div>
                <div class="product-price">${Number(p.price).toLocaleString()} đ</div>
                <button class="btn-cart" onclick="addToCart('${p.id}')">Thêm vào giỏ</button>
            </div>
        </div>`;
    });
    document.getElementById('products-catalog').innerHTML = html || "<p>Không có sản phẩm.</p>";
}

function fetchAndRenderProducts() {
    const searchVal = document.getElementById('search-product').value || "";

    // Lấy category từ select (ưu tiên URL)
    let filterCat = document.getElementById('filter-category').value;
    const urlParams = new URLSearchParams(window.location.search);
    const urlCat = urlParams.get('category');
    if (urlCat) filterCat = urlCat;

    // Nếu có category trên URL mà select khác giá trị thì cập nhật luôn
    if (urlCat && document.getElementById('filter-category').value !== urlCat) {
        document.getElementById('filter-category').value = urlCat;
    }

    const params = new URLSearchParams();
    params.append('q', searchVal);
    params.append('category', filterCat);

    fetch('/HeThongChamSocThuCung/backend/api/products/api_get_products.php?' + params.toString())
    .then(res => res.json())
    .then(data => renderProducts(data));
}

document.addEventListener('DOMContentLoaded', function() {
    // Khi load trang, nếu có category trên URL thì set vào select luôn
    const urlParams = new URLSearchParams(window.location.search);
    const urlCat = urlParams.get('category');
    if (urlCat && document.getElementById('filter-category')) {
        document.getElementById('filter-category').value = urlCat;
    }

    fetchAndRenderProducts();
    document.getElementById('search-product').addEventListener('input', fetchAndRenderProducts);
    document.getElementById('filter-category').addEventListener('change', fetchAndRenderProducts);
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
