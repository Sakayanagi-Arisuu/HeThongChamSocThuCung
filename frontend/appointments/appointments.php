<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$page_title = "Danh sách lịch hẹn";
include '../../includes/header.php';
include '../../includes/navbar_customer.php';
?>
<link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/appointments/appointments.css">

<div class="main-content">
    <div style="flex:2">
        <div id="appointments-table"></div>
    </div>
    <div class="sidebar-right">
        <?php include '../../includes/category.php'; ?>
    </div>
</div>

<!-- Popup thanh toán QR -->
<div id="payment-modal">
  <div class="payment-popup">
    <button onclick="closePaymentModal()" class="close-btn">&times;</button>
    <h3 style="margin:10px 0 14px 0; color:#29b6f6; font-size:21px;">Vui lòng quét mã QR để thanh toán</h3>
    <img id="qrImage" src="/HeThongChamSocThuCung/assets/images/qr_code.png" alt="QR Code">
    <div>
      <button id="btnPaid" class="checkout-btn">Đã thanh toán</button>
    </div>
  </div>
</div>

<!-- Popup xem chi tiết medical record -->
<div id="medical-record-modal">
  <div class="form-content">
    <button onclick="closeMedicalRecordModal()" class="close-btn">&times;</button>
    <h3>Chi tiết hồ sơ khám bệnh</h3>
    <div id="medical-record-detail" style="margin-top:10px; font-size:16px;">
      <!-- Nội dung medical record sẽ được JS fill vào -->
    </div>
  </div>
</div>

<!-- Popup Đánh giá dịch vụ -->
<div id="feedback-modal">
  <div class="feedback-content">
    <button onclick="closeFeedbackModal()" class="close-btn">&times;</button>
    <h3 style="margin-top:0;">Đánh giá dịch vụ</h3>
    <form id="feedback-form">
      <input type="hidden" name="appointment_id">
      <input type="hidden" name="doctor_id">
      <div style="margin-bottom:10px;">
        <label>Chọn số sao:</label>
        <select name="rating" required style="font-size:18px; padding:4px 10px;">
            <option value="">Chọn</option>
            <option value="5">★★★★★ Tuyệt vời</option>
            <option value="4">★★★★☆ Tốt</option>
            <option value="3">★★★☆☆ Bình thường</option>
            <option value="2">★★☆☆☆ Kém</option>
            <option value="1">★☆☆☆☆ Rất tệ</option>
        </select>
      </div>
      <div style="margin-bottom:12px;">
        <label>Nhận xét:</label>
        <textarea name="comment" rows="3" style="width:100%;"></textarea>
      </div>
      <button type="submit" class="btn-action" style="background:#1976d2;color:#fff;width:100%;font-size:17px;">Gửi đánh giá</button>
    </form>
  </div>
</div>

<script src="/HeThongChamSocThuCung/assets/js/appointments/appointments.js"></script>
<?php
include '../../includes/footer.php';
?>
