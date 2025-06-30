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
<style>
#doctor-appointments-table {
    margin: 32px 0 0 0;
    width: 100%;
    max-width: 900px;
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
#doctor-appointments-table table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,160,200,0.10), 0 1.5px 4px rgba(0,0,0,0.08);
    font-size: 16px;
    margin-top: 12px;
}
#doctor-appointments-table th, #doctor-appointments-table td {
    border: 1px solid #e0eaf1;
    padding: 10px 12px;
    text-align: center;
}
#doctor-appointments-table th {
    background: #29b6f6;
    color: #fff;
    font-weight: bold;
    letter-spacing: 0.5px;
}
#doctor-appointments-table tr:nth-child(even) {
    background: #f4fafd;
}
#doctor-appointments-table tr:hover {
    background: #e0f7fa;
}
#doctor-appointments-table h2 {
    color: #009fe3;
    font-size: 28px;
    margin-bottom: 10px;
    margin-top: 0;
}
.status-huy { color: #b71c1c; font-weight:bold; }
.status-hoanthanh { color: #388e3c; font-weight:bold; }
.status-chuaxuly { color: #666; }
.status-dadat { color: #1976d2; }
.status-checkin { color: #ff9800; font-weight:bold; }
.btn-kham {
    padding: 6px 15px; border-radius: 5px; font-weight: 600; border: none; cursor: pointer;
    background: #e0f7fa; color: #1976d2; transition: background .15s;
}
.btn-kham:hover { background: #b5eaff; }

/* Popup medical form */
#medical-form-modal {
    display:none; position:fixed; top:0; left:0; width:100vw; height:100vh;
    background:rgba(0,0,0,0.13); align-items:center; justify-content:center; z-index:9999;
}
#medical-form-modal .form-content {
    background:#fff; border-radius:12px; box-shadow:0 2px 16px #0002; padding:28px 32px; min-width:340px; position:relative;
    animation:popupOpen .23s;
}
@keyframes popupOpen { from {transform:scale(.95);opacity:0;} to {transform:scale(1);opacity:1;} }
#medical-form-modal h3 {margin-top:0;}
#medical-form-modal label {display:block; margin-top:10px;}
#medical-form-modal textarea, #medical-form-modal input[type="number"] {
    width:100%; border:1px solid #c6e1ee; border-radius:5px; padding:5px 7px; margin-top:2px;
}
#medical-form-modal button[type="submit"] {
    margin-top:14px; width:100%; padding:10px; border-radius:7px; border:none; background:#0099cc; color:#fff; font-weight:bold; font-size:17px; cursor:pointer;
}
#medical-form-modal button[type="submit"]:hover { background:#1976d2; }
#medical-form-modal .close-btn {
    position:absolute;right:18px;top:14px;background:none;border:none;font-size:26px;color:#999;cursor:pointer;
}
#medical-form-msg { margin-top:9px; color:#27ae60; text-align:center; display:none;}
</style>

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

<script>
function openMedicalForm(appointment_id, service) {
  document.getElementById('medical-form-modal').style.display = 'flex';
  document.getElementById('medical-appointment-id').value = appointment_id;
  document.getElementById('medical-service').value = service;
  document.getElementById('medical-record-form').reset();
  document.getElementById('medical-form-msg').style.display = 'none';
  // Nếu là "Khác", show trường phí dịch vụ, ngược lại ẩn
  if (service.trim() === 'Khác') {
    document.getElementById('fee-input-box').style.display = '';
    document.getElementById('fee-input').required = true;
  } else {
    document.getElementById('fee-input-box').style.display = 'none';
    document.getElementById('fee-input').required = false;
  }
}
function closeMedicalForm() {
  document.getElementById('medical-form-modal').style.display = 'none';
}

// Xử lý submit ghi nhận khám AJAX
document.getElementById('medical-record-form').onsubmit = function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch('/HeThongChamSocThuCung/backend/api/doctor/medical_records/api_create_medical_record.php', {
    method: 'POST',
    body: formData,
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      document.getElementById('medical-form-msg').textContent = 'Đã lưu hồ sơ!';
      document.getElementById('medical-form-msg').style.display = 'block';
      setTimeout(()=>{
        closeMedicalForm();
        location.reload();
      }, 1100);
    } else {
      alert(data.error || 'Có lỗi!');
    }
  })
  .catch(()=>alert('Lỗi kết nối!'));
};
</script>
<?php

include '../../includes/footer.php';
?>