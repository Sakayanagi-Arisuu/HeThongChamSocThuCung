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