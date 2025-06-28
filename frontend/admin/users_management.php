<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$page_title = "Quản lý người dùng - Pet Care Services";
include '../../includes/header.php';
include '../../includes/navbar_admin.php';
?>
<style>
#users-table {
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
#users-table table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,160,200,0.10), 0 1.5px 4px rgba(0,0,0,0.08);
    font-size: 16px;
    margin-top: 12px;
}
#users-table th, #users-table td {
    border: 1px solid #e0eaf1;
    padding: 10px 12px;
    text-align: center;
}
#users-table th {
    background: #29b6f6;
    color: #fff;
    font-weight: bold;
    letter-spacing: 0.5px;
}
#users-table tr:nth-child(even) {
    background: #f4fafd;
}
#users-table tr:hover {
    background: #e0f7fa;
}
#users-table h2 {
    color: #009fe3;
    font-size: 28px;
    margin-bottom: 10px;
    margin-top: 0;
}
#users-table .add-user-link {
    display: inline-block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #00bcd4;
    text-decoration: none;
    font-size: 18px;
    transition: color 0.15s;
}
#users-table .add-user-link:hover {
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

#user-form-modal {
    display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:999;
    background:rgba(0,0,0,0.15);align-items:center;justify-content:center;
}
#user-form-modal .user-form-content {
    background:#fff;border-radius:12px;box-shadow:0 2px 8px #0002;
    padding:24px;width:350px;position:relative;
    animation: popupOpen .25s;
}
@keyframes popupOpen {
    from {transform:scale(0.95); opacity:0;}
    to {transform:scale(1); opacity:1;}
}
#user-form-modal h3 { margin-top:0; }
#user-form-modal label { display:block; margin-bottom:7px;}
#user-form-modal input, #user-form-modal select {
    width:100%; border:1px solid #c6e1ee; border-radius:5px; margin-top:3px; margin-bottom:8px; padding:4px 7px;
}
#user-form-modal button[type="submit"] {
    margin-top:10px; width:100%; padding:8px; border-radius:7px; border:none; background:#29b6f6; color:#fff; font-weight:600; font-size:16px; cursor:pointer;
}
#user-form-modal button[type="submit"]:hover { background: #1976d2; }
#user-form-modal .close-btn {
    position:absolute;right:12px;top:12px;background:none;border:none;
    font-size:24px;color:#999;cursor:pointer;
}
</style>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="main-content">
    <div style="flex: 2;">
        <div id="users-table"></div>
    </div>
    <div class="sidebar-right">
        <?php include '../../includes/category.php'; ?>
    </div>
</div>

<!-- Popup form thêm/sửa người dùng -->
<div id="user-form-modal">
  <div class="user-form-content">
    <button onclick="closeUserForm()" class="close-btn">&times;</button>
    <h3 id="user-form-title"></h3>
    <form id="user-form">
      <input type="hidden" name="id" />
      <label>Tên đăng nhập: <input name="username" required></label>
      <label>Họ tên: <input name="full_name"></label>
      <label>Vai trò:
        <select name="role" required>
          <option value="admin">Admin</option>
          <option value="doctor">Bác sĩ</option>
          <option value="customer">Khách hàng</option>
        </select>
      </label>
      <label>Mật khẩu mới: <input type="password" name="password" autocomplete="new-password"></label>
      <button type="submit" id="user-form-submit">Lưu</button>
    </form>
  </div>
</div>

<script>
function getRoleNameVi(role) {
    switch(role) {
        case 'admin': return 'Quản trị viên';
        case 'doctor': return 'Bác sĩ';
        case 'customer': return 'Khách hàng';
        case 'guest': return 'Khách';
        default: return role;
    }
}
function openUserForm(mode, user = {}) {
    document.getElementById('user-form-modal').style.display = 'flex';
    document.getElementById('user-form-title').innerText = (mode === 'add') ? 'Thêm người dùng' : 'Sửa người dùng';
    document.getElementById('user-form-submit').innerText = (mode === 'add') ? 'Thêm' : 'Cập nhật';

    // Reset form
    const form = document.getElementById('user-form');
    form.reset();
    form.mode = mode;

    // Điền dữ liệu nếu là edit
    form.elements['id'].value = user.id || '';
    form.elements['username'].value = user.username || '';
    form.elements['full_name'].value = user.full_name || '';
    form.elements['role'].value = user.role || 'customer';
    form.elements['password'].value = '';
    // Nếu là edit thì disable username
    form.elements['username'].disabled = (mode === 'edit');
}
function closeUserForm() {
    document.getElementById('user-form-modal').style.display = 'none';
}

// Xử lý submit form AJAX
document.addEventListener('DOMContentLoaded', function() {
    loadUserTable();
    document.getElementById('user-form').onsubmit = function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        let url = '/HeThongChamSocThuCung/backend/api/admin/users_management/api_create_user.php';
        if (form.mode === 'edit') {
            url = '/HeThongChamSocThuCung/backend/api/admin/users_management/api_edit_user.php';
        }
        fetch(url, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                closeUserForm();
                loadUserTable();
            } else {
                alert(data.error || 'Có lỗi xảy ra!');
            }
        });
    };
});

// Hàm tải bảng users-table
function loadUserTable() {
    fetch('/HeThongChamSocThuCung/backend/api/admin/users_management/api_get_users.php')
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById('users-table').innerHTML = "<p>" + data.error + "</p>";
                return;
            }
            let html = `
                <h2>Danh sách người dùng</h2>
                <a href="javascript:void(0)" onclick="openUserForm('add')" class="add-user-link">+ Thêm người dùng</a>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Vai trò</th>
                        <th>Hành động</th>
                    </tr>
            `;
            for (let user of data) {
                html += `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.full_name || ''}</td>
                    <td>${getRoleNameVi(user.role)}</td>
                    <td>
                        <button type="button" class="action-btn action-edit" title="Sửa"
                          onclick='openUserForm("edit", ${JSON.stringify(user)})'>
                          <span class="material-icons">edit</span> Sửa
                        </button>
                        <button type="button" class="action-btn action-delete" title="Xóa"
                          onclick="if(confirm('Xóa người dùng này?')) deleteUser(${user.id})">
                          <span class="material-icons">delete</span> Xóa
                        </button>
                    </td>
                </tr>
                `;
            }
            html += '</table>';
            document.getElementById('users-table').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('users-table').innerHTML = "<p>Lỗi kết nối server!</p>";
        });
}

// Xử lý xóa user
function deleteUser(id) {
    fetch('/HeThongChamSocThuCung/backend/api/admin/users_management/api_delete_user.php', {
        method: 'POST',
        body: new URLSearchParams({id:id}),
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.success) loadUserTable();
        else alert(data.error||'Lỗi!');
    });
}
</script>
