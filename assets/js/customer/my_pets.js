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
