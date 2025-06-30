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
<style>
#appointments-table { margin: 32px 0 0 0; width: 100%; max-width: 980px; }
#appointments-table table {
    border-collapse: collapse; width: 100%; background: #fff;
    border-radius: 12px; box-shadow: 0 4px 16px rgba(0,160,200,0.10);
    font-size: 16px; margin-top: 12px;
}
#appointments-table th, #appointments-table td { border: 1px solid #e0eaf1; padding: 10px 12px; text-align: center; }
#appointments-table th { background: #29b6f6; color: #fff; }
#appointments-table tr:nth-child(even) { background: #f4fafd; }
#appointments-table tr:hover { background: #e0f7fa; }
.btn-action {
    padding: 6px 15px; border-radius: 5px; font-weight: 600; border: none; cursor: pointer; margin: 2px;
    background: #ececec; color: #1976d2; transition: background .15s;
}
.btn-action:hover { background: #b5eaff; }
.btn-cancel { background: #ffeaea; color: #d81b60; }
.btn-cancel:hover { background: #ffd5d5; }
.btn-delete { background: #eee; color: #e53935;}
.btn-delete:hover { background: #ffd5d5;}
.status-huy { color: #b71c1c; font-weight:bold; }
.status-hoanthanh { color: #388e3c; font-weight:bold; }
.status-chuaxuly { color: #666; }
.status-dadat { color: #1976d2; }
#appointments-table .add-pet-link {
    display: inline-block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #00bcd4;
    text-decoration: none;
    font-size: 18px;
    transition: color 0.15s;
}
#appointments-table .add-pet-link:hover {
    color: #e64a19;
}
/* Popup thanh toán QR */
#payment-modal {
    display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:999;
    background:rgba(0,0,0,0.14); align-items:center; justify-content:center;
}
#payment-modal .payment-popup {
    background:#fff; border-radius:13px; box-shadow:0 3px 16px #0002;
    padding:34px 30px 22px 30px; min-width:300px; max-width:96vw; text-align:center; position:relative;
    animation:popupOpen .24s;
}
@keyframes popupOpen {
    from {transform:scale(0.96); opacity:0;}
    to {transform:scale(1); opacity:1;}
}
#payment-modal .close-btn {
    position:absolute; top:12px; right:14px; border:none; background:none; font-size:25px; color:#bbb; cursor:pointer;
}
#payment-modal img {
    width:180px; border-radius:10px; margin:16px 0 12px 0; border:2px solid #e3e7ee;
}
/* Nút thanh toán giống cart */
.checkout-btn {
    background: #29b6f6;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 600;
    padding: 10px 28px;
    margin-top: 18px;
    cursor: pointer;
    transition: background 0.16s;
    box-shadow: 0 2px 8px #1aaee2a8;
    min-width: 130px;
}
.checkout-btn:hover {
    background: #1976d2;
}

/* Popup xem medical record */
#medical-record-modal {
    display:none; position:fixed; top:0; left:0; width:100vw; height:100vh;
    background:rgba(0,0,0,0.13); align-items:center; justify-content:center; z-index:9999;
}
#medical-record-modal .form-content {
    background:#fff; border-radius:12px; box-shadow:0 2px 16px #0002; padding:28px 32px; min-width:340px; position:relative;
    animation:popupOpen .23s;
}
#medical-record-modal .close-btn {
    position:absolute;right:18px;top:14px;background:none;border:none;font-size:26px;color:#999;cursor:pointer;
}
</style>

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
    <img id="qrImage" src="/HeThongChamSocThuCung/images/qr_code.png" alt="QR Code">
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

<script>
let currentPaymentAppointmentId = null;
function openPaymentModal(appointmentId) {
    currentPaymentAppointmentId = appointmentId;
    document.getElementById('payment-modal').style.display = 'flex';
}
function closePaymentModal() {
    document.getElementById('payment-modal').style.display = 'none';
    currentPaymentAppointmentId = null;
}
document.addEventListener('DOMContentLoaded', function() {
    loadAppointmentsTable();
    document.getElementById('btnPaid').onclick = function() {
        if (!currentPaymentAppointmentId) return;
        fetch('/HeThongChamSocThuCung/backend/api/customer/appointments/api_paid_appointment.php', {
            method:'POST',
            body: new URLSearchParams({id: currentPaymentAppointmentId})
        }).then(res=>res.json()).then(data=>{
            if(data.success) {
                closePaymentModal();
                loadAppointmentsTable();
                alert("Thanh toán thành công!");
            } else {
                alert(data.error||'Lỗi cập nhật!');
            }
        });
    }
});

function loadAppointmentsTable() {
    fetch('/HeThongChamSocThuCung/backend/api/customer/appointments/api_get_appointments.php')
    .then(res=>res.json())
    .then(data=>{
        let html = `
            <h2>Danh sách lịch hẹn</h2>
            <a href="book_appointment.php" class="add-pet-link">+ Đặt lịch mới</a>
            <table>
                <tr>
                    <th>Thú cưng</th>
                    <th>Bác sĩ</th>
                    <th>Thời gian</th>
                    <th>Tình trạng</th>
                    <th>Lý do</th>
                    <th>Hành động</th>
                </tr>
        `;
        if (!data || data.length === 0) {
            html += `<tr><td colspan="6">Chưa có lịch hẹn nào!</td></tr>`;
        } else {
            data.forEach(row=>{
                let statusClass = '';
                if (row.status==='Đã hủy') statusClass='status-huy';
                else if (row.status==='Đã khám') statusClass='status-hoanthanh';
                else if (row.status==='Đã đặt') statusClass='status-dadat';

                html += `<tr>
                    <td>${row.pet_name}</td>
                    <td>${row.doctor_name}</td>
                    <td>${row.schedule_time}</td>
                    <td class="${statusClass}">${row.status}</td>
                    <td>${row.reason||''}</td>
                    <td>`;

                if (row.status === 'Đã đặt') {
                    html += `
                        <button class="btn-action btn-cancel" onclick="cancelAppointment(${row.id})">Huỷ</button>
                        <button class="btn-action" onclick="checkInAppointment(${row.id})">Check-in</button>
                    `;
                }
                if (row.status === 'Đã hủy') {
                    html += `<button class="btn-delete" onclick="deleteAppointment(${row.id})">Xóa</button>`;
                }
                // Đã khám + chưa thanh toán: nút Thanh toán + Xem chi tiết
                if (row.status === 'Đã khám' && (!row.payment_status || row.payment_status==='Chưa thanh toán')) {
                    html += `<button class="checkout-btn" onclick="openPaymentModal(${row.id})">Thanh toán</button>
                             <button class="btn-action" onclick="openMedicalRecordModal(${row.id})">Xem chi tiết</button>`;
                }
                // Đã khám + đã thanh toán: chỉ hiện trạng thái + nút Xem chi tiết
                if (row.status === 'Đã khám' && row.payment_status==='Đã thanh toán') {
                    html += `<span style="color:#388e3c;font-weight:bold;">Đã thanh toán</span>
                             <button class="btn-action" onclick="openMedicalRecordModal(${row.id})">Xem chi tiết</button>`;
                }
                html += `</td></tr>`;
            });
        }
        html+='</table>';
        document.getElementById('appointments-table').innerHTML = html;
    });
}

function cancelAppointment(id) {
    if (!confirm('Bạn chắc chắn muốn hủy lịch hẹn này?')) return;
    fetch('/HeThongChamSocThuCung/backend/api/customer/appointments/api_cancel_appointment.php', {
        method:'POST', body: new URLSearchParams({id:id}),
    }).then(res=>res.json()).then(data=>{
        if (data.success) loadAppointmentsTable();
        else alert(data.error||'Lỗi');
    });
}
function deleteAppointment(id) {
    if (!confirm('Xóa lịch hẹn này?')) return;
    fetch('/HeThongChamSocThuCung/backend/api/customer/appointments/api_delete_appointment.php', {
        method:'POST', body: new URLSearchParams({id:id}),
    }).then(res=>res.json()).then(data=>{
        if (data.success) loadAppointmentsTable();
        else alert(data.error||'Lỗi');
    });
}
function checkInAppointment(id) {
    fetch('/HeThongChamSocThuCung/backend/api/customer/appointments/api_checkin_appointment.php', {
        method:'POST', body: new URLSearchParams({id:id}),
    }).then(res=>res.json()).then(data=>{
        if (data.success) loadAppointmentsTable();
        else alert(data.error||'Lỗi check-in!');
    });
}

// Xem chi tiết medical record
function openMedicalRecordModal(appointmentId) {
    document.getElementById('medical-record-modal').style.display = 'flex';
    document.getElementById('medical-record-detail').innerHTML = 'Đang tải...';
    fetch('/HeThongChamSocThuCung/backend/api/doctor/medical_records/api_get_medical_record.php?id=' + appointmentId)
        .then(res => res.json())
        .then(data => {
            if (!data || !data.success || !data.record) {
                document.getElementById('medical-record-detail').innerHTML = '<div style="color:#e53935">Không tìm thấy hồ sơ!</div>';
                return;
            }
            const record = data.record;
            document.getElementById('medical-record-detail').innerHTML = `
                <b>Chẩn đoán:</b><br>
                <div style="white-space:pre-line; margin-bottom:8px; border:1px solid #e6e6e6; border-radius:5px; padding:7px 10px; background:#f9f9f9;">${record.diagnosis || ''}</div>
                <b>Lịch trình điều trị:</b><br>
                <div style="white-space:pre-line; margin-bottom:8px; border:1px solid #e6e6e6; border-radius:5px; padding:7px 10px; background:#f9f9f9;">${record.treatment || ''}</div>
                <b>Ghi chú thêm:</b><br>
                <div style="white-space:pre-line; margin-bottom:8px; border:1px solid #e6e6e6; border-radius:5px; padding:7px 10px; background:#f9f9f9;">${record.notes || ''}</div>
                <b>Phí dịch vụ:</b> <span style="font-weight:500">${record.fee ? (parseInt(record.fee).toLocaleString() + " đ") : "0 đ"}</span>
            `;
        })
        .catch(()=>{
            document.getElementById('medical-record-detail').innerHTML = '<div style="color:#e53935">Lỗi kết nối server!</div>';
        });
}
function closeMedicalRecordModal() {
    document.getElementById('medical-record-modal').style.display = 'none';
}
</script>
<?php
include '../../includes/footer.php';
?>
