function openCheckout() {
    // Kiểm tra thông tin bắt buộc
    if (!USER_INFO.contact_info || !USER_INFO.address) {
        alert('Bạn cần bổ sung đầy đủ số điện thoại/email và địa chỉ nhận hàng để thanh toán!\nNhấn OK để chuyển đến trang cập nhật thông tin cá nhân.');
        window.location.href = "/HeThongChamSocThuCung/frontend/users/profile.php";
        return;
    }
    // Điền sẵn thông tin vào popup
    document.getElementById('checkout-contact').value = USER_INFO.contact_info;
    document.getElementById('checkout-address').value = USER_INFO.address;
    document.getElementById('checkout-modal').style.display = 'flex';
}
function closeCheckout() {
    document.getElementById('checkout-modal').style.display = 'none';
}
function doneCheckout() {
    const address = document.getElementById('checkout-address').value.trim();
    if (!address) {
        alert('Vui lòng nhập địa chỉ nhận hàng!');
        return;
    }
    fetch('/HeThongChamSocThuCung/backend/api/products/cart/checkout_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'address=' + encodeURIComponent(address)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Thanh toán thành công!');
            window.location.reload();
        } else {
            alert(data.error || 'Lỗi!');
        }
        closeCheckout();
    })
    .catch(() => {
        alert('Có lỗi kết nối server!');
        closeCheckout();
    });
}