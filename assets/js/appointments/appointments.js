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
                let statusText = row.status;
                if (row.status==='Đã hủy') statusClass='status-huy';
                else if (row.status==='Đã khám') statusClass='status-hoanthanh';
                else if (row.status==='Đã đặt') statusClass='status-dadat';

                // Thêm hiển thị "Đã thanh toán" vào cột tình trạng
                if (row.status === 'Đã khám' && row.payment_status==='Đã thanh toán') {
                    statusClass = 'status-hoanthanh';
                    statusText = 'Đã khám - Đã thanh toán';
                }

                html += `<tr>
                    <td>${row.pet_name}</td>
                    <td>${row.doctor_name}</td>
                    <td>${row.schedule_time}</td>
                    <td class="${statusClass}">${statusText}</td>
                    <td>${row.reason||''}</td>
                    <td><div class="action-buttons">`;

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
                // Đã khám + đã thanh toán: chỉ hiện Xem chi tiết + Đánh giá hoặc số sao
                if (row.status === 'Đã khám' && row.payment_status==='Đã thanh toán') {
                    html += `<button class="btn-action" onclick="openMedicalRecordModal(${row.id})">Xem chi tiết</button>`;
                    if (!row.has_feedback || row.has_feedback == 0) {
                        html += `<button class="btn-action" onclick="openFeedbackModal(${row.id}, '${row.doctor_id||''}')">Đánh giá</button>`;
                    } else if (row.feedback_rating && row.feedback_rating > 0) {
                        html += `<span class="rating-stars">`;
                        for(let i=1;i<=5;i++) {
                            html += (i <= row.feedback_rating ? '★' : '☆');
                        }
                        html += `</span>`;
                    }
                }
                html += `</div></td></tr>`;
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

// ==== Đánh giá dịch vụ ====
function openFeedbackModal(appointmentId, doctorId) {
    document.getElementById('feedback-modal').style.display = 'flex';
    const form = document.getElementById('feedback-form');
    form.appointment_id.value = appointmentId;
    form.doctor_id.value = doctorId;
}
function closeFeedbackModal() {
    document.getElementById('feedback-modal').style.display = 'none';
}
document.getElementById('feedback-form').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    fetch('/HeThongChamSocThuCung/backend/api/customer/feedbacks/api_create_feedback.php', {
        method:'POST',
        body: formData
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.success){
            closeFeedbackModal();
            alert("Đánh giá thành công!");
            loadAppointmentsTable();
        }else{
            alert(data.error||'Lỗi gửi đánh giá!');
        }
    });
};