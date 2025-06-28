<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$page_title = "Pet Care Services";
include '../../includes/header.php';
include '../../includes/navbar_customer.php';
?>
<style>
#pets-table {
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
#pets-table table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,160,200,0.10), 0 1.5px 4px rgba(0,0,0,0.08);
    font-size: 16px;
    margin-top: 12px;
}
#pets-table th, #pets-table td {
    border: 1px solid #e0eaf1;
    padding: 10px 12px;
    text-align: center;
}
#pets-table th {
    background: #29b6f6;
    color: #fff;
    font-weight: bold;
    letter-spacing: 0.5px;
}
#pets-table tr:nth-child(even) {
    background: #f4fafd;
}
#pets-table tr:hover {
    background: #e0f7fa;
}
#pets-table h2 {
    color: #009fe3;
    font-size: 28px;
    margin-bottom: 10px;
    margin-top: 0;
}
#pets-table .add-pet-link {
    display: inline-block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #00bcd4;
    text-decoration: none;
    font-size: 18px;
    transition: color 0.15s;
}
#pets-table .add-pet-link:hover {
    color: #e64a19;
}

/* Nút hành động đẹp */
.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border: none;
    border-radius: 6px;
    padding: 5px 14px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.12s;
    margin: 0 2px;
}
.action-edit {
    background: #fff4e3;
    color: #ef6c00;
    border: 1.5px solid #ffd3a2;
}
.action-edit:hover {
    background: #ffe0b2;
    color: #d84315;
}
.action-delete {
    background: #ffe6e9;
    color: #d32f2f;
    border: 1.5px solid #ffbfc3;
}
.action-delete:hover {
    background: #ffcdd2;
    color: #b71c1c;
}
.material-icons {
    font-family: 'Material Icons';
    font-style: normal;
    font-weight: normal;
    font-size: 20px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    direction: ltr;
    -webkit-font-feature-settings: 'liga';
    -webkit-font-smoothing: antialiased;
}
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');

/* Popup form style giữ nguyên ... */
#pet-form-modal {
    display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:999;
    background:rgba(0,0,0,0.15);align-items:center;justify-content:center;
}
#pet-form-modal .pet-form-content {
    background:#fff;border-radius:12px;box-shadow:0 2px 8px #0002;
    padding:24px;width:350px;position:relative;
    animation: popupOpen .25s;
}
@keyframes popupOpen {
    from {transform:scale(0.95); opacity:0;}
    to {transform:scale(1); opacity:1;}
}
#pet-form-modal h3 { margin-top:0; }
#pet-form-modal label { display:block; margin-bottom:7px;}
#pet-form-modal input, #pet-form-modal textarea, #pet-form-modal select {
    width:100%; border:1px solid #c6e1ee; border-radius:5px; margin-top:3px; margin-bottom:8px; padding:4px 7px;
}
#pet-form-modal button[type="submit"] {
    margin-top:10px; width:100%; padding:8px; border-radius:7px; border:none; background:#29b6f6; color:#fff; font-weight:600; font-size:16px; cursor:pointer;
}
#pet-form-modal button[type="submit"]:hover { background: #1976d2; }
#pet-form-modal .close-btn {
    position:absolute;right:12px;top:12px;background:none;border:none;
    font-size:24px;color:#999;cursor:pointer;
}
</style>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="main-content">
    <div style="flex: 2;">
        <div id="pets-table"></div>
    </div>
    <div class="sidebar-right">
        <?php include '../../includes/category.php'; ?>
    </div>
</div>

<!-- Popup form thêm/sửa thú cưng -->
<div id="pet-form-modal">
  <div class="pet-form-content">
    <button onclick="closePetForm()" class="close-btn">&times;</button>
    <h3 id="pet-form-title"></h3>
    <form id="pet-form">
      <input type="hidden" name="id" />
      <label>Tên: <input name="name" required></label>
      <label>Loài: <input name="species"></label>
      <label>Giống: <input name="breed"></label>
      <label>Ngày sinh: <input type="date" name="birth_date"></label>
      <label>Giới tính:
        <select name="gender">
          <option>Đực</option>
          <option>Cái</option>
        </select>
      </label>
      <label>Cân nặng(kg): <input type="number" step="0.1" name="weight"></label>
      <label>Ghi chú: <textarea name="notes"></textarea></label>
      <button type="submit" id="pet-form-submit">Lưu</button>
    </form>
  </div>
</div>

<script>
function openPetForm(mode, pet = {}) {
    document.getElementById('pet-form-modal').style.display = 'flex';
    document.getElementById('pet-form-title').innerText = (mode === 'add') ? 'Thêm thú cưng' : 'Sửa thú cưng';
    document.getElementById('pet-form-submit').innerText = (mode === 'add') ? 'Thêm' : 'Cập nhật';

    // Reset form
    const form = document.getElementById('pet-form');
    form.reset();
    form.mode = mode;

    // Điền dữ liệu nếu là edit
    form.elements['id'].value = pet.id || '';
    form.elements['name'].value = pet.name || '';
    form.elements['species'].value = pet.species || '';
    form.elements['breed'].value = pet.breed || '';
    form.elements['birth_date'].value = pet.birth_date || '';
    form.elements['gender'].value = pet.gender || 'Đực';
    form.elements['weight'].value = pet.weight || '';
    form.elements['notes'].value = pet.notes || '';
}
function closePetForm() {
    document.getElementById('pet-form-modal').style.display = 'none';
}

// Xử lý submit form AJAX
document.addEventListener('DOMContentLoaded', function() {
    loadPetTable();
    document.getElementById('pet-form').onsubmit = function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        let url = '/HeThongChamSocThuCung/backend/api/customer/pets/api_create_pet.php';
        if (form.mode === 'edit') {
            url = '/HeThongChamSocThuCung/backend/api/customer/pets/api_edit_pet.php';
        }
        fetch(url, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closePetForm();
                loadPetTable();
            } else {
                alert(data.error || 'Có lỗi xảy ra!');
            }
        });
    };
});

// Hàm tải bảng pets-table
function loadPetTable() {
    fetch('/HeThongChamSocThuCung/backend/api/customer/pets/api_get_pets.php')
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById('pets-table').innerHTML = "<p>" + data.error + "</p>";
                return;
            }
            let html = `
                <h2>Danh sách thú cưng</h2>
                <a href="javascript:void(0)" onclick="openPetForm('add')" class="add-pet-link">+ Thêm thú cưng</a>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Loài</th>
                        <th>Giống</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Cân nặng(kg)</th>
                        <th>Ghi chú</th>
                        <th>Hành động</th>
                    </tr>
            `;
            for (let pet of data) {
                html += `
                <tr>
                    <td>${pet.id}</td>
                    <td>${pet.name}</td>
                    <td>${pet.species}</td>
                    <td>${pet.breed}</td>
                    <td>${pet.birth_date}</td>
                    <td>${pet.gender}</td>
                    <td>${pet.weight}</td>
                    <td>${pet.notes}</td>
                    <td>
                        <button type="button" class="action-btn action-edit" title="Sửa"
                          onclick='openPetForm("edit", ${JSON.stringify(pet)})'>
                          <span class="material-icons">edit</span> Sửa
                        </button>
                        <button type="button" class="action-btn action-delete" title="Xóa"
                          onclick="if(confirm('Xóa thú cưng này?')) window.location.href='../../backend/api/customer/pets/pet_delete.php?id=${pet.id}'">
                          <span class="material-icons">delete</span> Xóa
                        </button>
                    </td>
                </tr>
                `;
            }
            html += '</table>';
            document.getElementById('pets-table').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('pets-table').innerHTML = "<p>Lỗi kết nối server!</p>";
        });
}
</script>
<?php

include '../../includes/footer.php';
?>