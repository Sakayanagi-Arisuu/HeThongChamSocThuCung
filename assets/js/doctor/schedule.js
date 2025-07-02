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