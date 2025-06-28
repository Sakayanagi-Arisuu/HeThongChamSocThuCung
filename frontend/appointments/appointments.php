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
</style>

<div class="main-content">
    <div style="flex:2">
        <div id="appointments-table"></div>
    </div>
    <div class="sidebar-right">
        <?php include '../../includes/category.php'; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAppointmentsTable();
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
                if (row.status === 'Đã khám') {
                    html += `<a href="payment.php?appointment_id=${row.id}" class="btn-action">Thanh toán</a>`;
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
</script>
