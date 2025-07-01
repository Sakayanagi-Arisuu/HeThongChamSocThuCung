<?php
if (!isset($_SESSION)) session_start();

$avatar = !empty($_SESSION['avatar'])
    ? $_SESSION['avatar']
    : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['full_name'] ?? $_SESSION['username'] ?? 'User');
$user_fullname = $_SESSION['full_name'] ?? $_SESSION['username'] ?? 'Bác sĩ';
?>
<style>
/* Giữ lại CSS giống customer, hoặc extract ra file chung nếu muốn */
.navbar-customer {
    background: #0099cc;
    padding: 0;
    min-height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.navbar-customer-menu {
    display: flex;
    align-items: center;
    flex: 1;
    justify-content: center;
}
.navbar-customer-menu a {
    color: white;
    text-decoration: none;
    margin: 0 14px;
    font-weight: bold;
    font-size: 16px;
    padding: 14px 0 13px 0;
    border-bottom: 3px solid transparent;
    transition: border-color 0.2s;
}
.navbar-customer-menu a.active,
.navbar-customer-menu a:hover {
    border-bottom: 3px solid #fff;
}
.navbar-customer-user {
    display: flex;
    align-items: center;
    position: absolute;
    right: 28px;
    top: 4px;
}
.navbar-customer-user img {
    width: 36px; height: 36px;
    border-radius: 50%;
    border: 2px solid #fff;
    object-fit: cover;
    margin-right: 8px;
    background: #fff;
}
.navbar-customer-user span {
    color: #fff;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
}
.navbar-user-dropdown {
    display: none;
    position: absolute;
    right: 0; top: 45px;
    background: #fff;
    box-shadow: 0 8px 24px #0002;
    border-radius: 8px;
    min-width: 160px;
    z-index: 9999;
    text-align: left;
}
.navbar-user-dropdown a {
    display: block;
    color: #0099cc;
    padding: 12px 18px;
    font-weight: normal;
    border-bottom: 1px solid #eee;
    text-decoration: none;
}
.navbar-user-dropdown a:last-child { border-bottom: none; color: #e74c3c;}
.navbar-user-dropdown a:hover { background: #f7f7f7; }
</style>

<div class="navbar-customer">
    <div class="navbar-customer-menu">
        <a href="/HeThongChamSocThuCung/frontend/dashboards/dashboard.php" <?= ($active_menu ?? '')=='home' ? 'class="active"' : '' ?>>Trang chủ</a>
        <a href="/HeThongChamSocThuCung/frontend/dashboards/about_us.php" <?= ($active_menu ?? '')=='about' ? 'class="active"' : '' ?>>Về chúng tôi</a>
        <a href="/HeThongChamSocThuCung/frontend/products/products_list.php" <?= ($active_menu ?? '')=='products' ? 'class="active"' : '' ?>>Danh mục sản phẩm</a>
        <a href="/HeThongChamSocThuCung/frontend/doctor/schedule.php" <?= ($active_menu ?? '')=='appointments' ? 'class="active"' : '' ?>>Lịch khám</a>
        <a href="/HeThongChamSocThuCung/frontend/doctor/view_feedbacks.php" <?= ($active_menu ?? '')=='doctor_feedbacks' ? 'class="active"' : '' ?>>Xem đánh giá</a>
    </div>
    <div class="navbar-customer-user" onclick="toggleUserDropdown(event)">
        <img src="<?= htmlspecialchars($avatar) ?>?v=<?= time() ?>" alt="avatar">
        <span><?= htmlspecialchars($user_fullname) ?></span>
        <div id="navbarUserDropdown" class="navbar-user-dropdown">
            <a href="/HeThongChamSocThuCung/frontend/users/profile.php">Thông tin cá nhân</a>
            <a href="/HeThongChamSocThuCung/backend/api/auth/logout.php">Đăng xuất</a>
        </div>
    </div>
</div>
<script>
function toggleUserDropdown(event) {
    event.stopPropagation();
    var dropdown = document.getElementById('navbarUserDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}
window.addEventListener('click', function(e) {
    var dropdown = document.getElementById('navbarUserDropdown');
    if (dropdown) dropdown.style.display = 'none';
});
</script>
