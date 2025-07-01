document.getElementById('book-form').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('/HeThongChamSocThuCung/backend/api/customer/appointments/api_create_appointment.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert("Đặt lịch thành công!");
            window.location.href = "appointments.php";
        } else {
            alert(data.error || "Có lỗi xảy ra!");
        }
    })
    .catch(() => alert("Lỗi kết nối máy chủ!"));
};