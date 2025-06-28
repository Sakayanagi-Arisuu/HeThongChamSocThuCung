<?php
session_start();
require_once "../../includes/db.php";
$page_title = "Đặt lịch khám - Pet Care Services";

if (!isset($_SESSION['username'])) {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}

// Lấy user id hiện tại
$current_user = $_SESSION['username'];
$user_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$user_stmt->bind_param("s", $current_user);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_id = $user_result->fetch_assoc()['id'];

// Lấy thú cưng của user này
$pets = $conn->query("SELECT id, name FROM pets WHERE owner_id = $user_id");

// Lấy danh sách bác sĩ
$doctors = $conn->query("SELECT id, username FROM users WHERE role = 'bác sĩ'");

include '../../includes/header.php';
include '../../includes/navbar_customer.php';
?>

<div class="main-content">
    <div class="left-content">
        <!-- Nội dung chính đặt lịch (giữ nguyên như trước) -->
        <div class="appointment-title">ĐẶT LỊCH KHÁM</div>
        <div class="desc">
            <span style="font-weight:bold; color:#444;">Dịch Vụ Chăm Sóc Thú Cưng PET</span> rất hân hạnh đón tiếp quý khách hàng tới thăm khám và điều trị cho thú cưng.
            Quý khách có thể đặt lịch khám trực tiếp qua điện thoại với số hotline:
            <span class="hotline">0999888123</span> hoặc <span class="online">đặt lịch khám online</span> trên website.
        </div>
        <div class="desc-green">
            Đặt lịch trước để chúng tôi phục vụ bạn tốt hơn
        </div>
        <form id="book-form" class="form-book" method="POST">
            <label>Chọn thú cưng</label>
            <select name="pet_id" required>
                <option value="">-- Chọn thú cưng --</option>
                <?php foreach ($pets as $row): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Số điện thoại liên hệ</label>
            <input type="tel" name="phone" placeholder="Số điện thoại..." required pattern="[0-9]{9,15}">

            <label>Bác sĩ</label>
            <select name="doctor_id" required>
                <option value="">-- Chọn bác sĩ --</option>
                <?php foreach ($doctors as $row): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['username']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Thời gian khám</label>
            <input type="datetime-local" name="schedule_time" required>

            <label>Chuyên khoa - Dịch vụ</label>
            <select name="service" required>
                <option value="">Chọn dịch vụ cần đặt lịch</option>
                <option value="Khám tổng quát">Khám tổng quát</option>
                <option value="Khám chuyên khoa">Khám chuyên khoa</option>
                <option value="Tiêm phòng">Tiêm phòng</option>
                <option value="Tắm, vệ sinh, cắt tỉa lông">Tắm, vệ sinh, cắt tỉa lông</option>
                <option value="Khác">Khác...</option>
            </select>

            <label>Lý do khám</label>
            <textarea name="reason" required></textarea>

            <button type="submit">ĐĂNG KÝ LỊCH KHÁM</button>
        </form>
    </div>

    <?php include '../../includes/category.php'; // SIDEBAR phải ?>
</div>

<script>
document.getElementById('book-form').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('../../backend/api/customer/appointments/api_create_appointment.php', {
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
</script>

<?php

include '../../includes/footer.php';
?>
