<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$page_title = "Lịch làm việc của bác sĩ - Pet Care Services";
include '../../includes/header.php';
include '../../includes/navbar_doctor.php';

require_once "../../includes/db.php";
// Lấy ID của doctor hiện tại
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$doctor_id = $stmt->get_result()->fetch_assoc()['id'];

$query = "SELECT a.id, a.schedule_time, a.status, a.reason, a.service, p.name AS pet_name, u.full_name AS customer_name
          FROM appointments a
          JOIN pets p ON a.pet_id = p.id
          JOIN users u ON a.customer_id = u.id
          WHERE a.doctor_id = ?
          ORDER BY a.schedule_time ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!-- css -->
<link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/doctor/schedule.css">

<div class="main-content">
    <div style="flex: 2;">
        <div id="doctor-appointments-table">
            <h2>Lịch làm việc của bạn</h2>
            <table>
                <tr>
                    <th>Thú cưng</th>
                    <th>Khách hàng</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th>Lý do</th>
                    <th>Dịch vụ</th>
                    <th>Hành động</th>
                </tr>
                <?php if ($result->num_rows == 0): ?>
                    <tr><td colspan="7" style="color:#888;">Chưa có lịch làm việc nào!</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()):
                        $statusClass = "";
                        if ($row['status'] === 'Đã hủy') $statusClass = "status-huy";
                        else if ($row['status'] === 'Đã khám') $statusClass = "status-hoanthanh";
                        else if ($row['status'] === 'Check-in') $statusClass = "status-checkin";
                        else if ($row['status'] === 'Đã đặt') $statusClass = "status-dadat";
                        else $statusClass = "status-chuaxuly";
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['pet_name']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['schedule_time']) ?></td>
                        <td class="<?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['reason']) ?></td>
                        <td><?= htmlspecialchars($row['service']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'Check-in'): ?>
                                <button class="btn-kham" onclick="openMedicalForm(<?= $row['id'] ?>, '<?= addslashes($row['service']) ?>')">Khám</button>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <div class="sidebar-right">
        <?php include '../../includes/category.php'; ?>
    </div>
</div>

<!-- Popup form ghi nhận thông tin khám -->
<div id="medical-form-modal">
  <div class="form-content">
    <button onclick="closeMedicalForm()" class="close-btn">&times;</button>
    <h3>Ghi nhận thông tin khám bệnh</h3>
    <form id="medical-record-form">
      <input type="hidden" name="appointment_id" id="medical-appointment-id">
      <input type="hidden" name="service" id="medical-service">
      <label>Chẩn đoán:</label>
      <textarea name="diagnosis" rows="3" required></textarea>
      <label>Lịch trình điều trị:</label>
      <textarea name="treatment" rows="3" required></textarea>
      <label>Ghi chú thêm:</label>
      <textarea name="notes" rows="2"></textarea>
      <div id="fee-input-box" style="display:none;">
        <label>Phí dịch vụ (VNĐ):</label>
        <input type="number" name="fee" min="0" step="100000" id="fee-input">
      </div>
      <button type="submit">Lưu hồ sơ khám</button>
    </form>
    <div id="medical-form-msg"></div>
  </div>
</div>

<!-- script -->
<script src="/HeThongChamSocThuCung/assets/js/doctor/schedule.js"></script>
<?php

include '../../includes/footer.php';
?>