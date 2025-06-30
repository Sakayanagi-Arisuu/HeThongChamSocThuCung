<style>
.navbar-guest {
    background: #0099cc;
    padding: 0;
    min-height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    box-shadow: 0 2px 8px #0099cc22;
}
.navbar-guest-menu {
    display: flex;
    align-items: center;
    flex: 1;
    justify-content: center;
}
.navbar-guest-menu a {
    color: white;
    text-decoration: none;
    margin: 0 14px;
    font-weight: bold;
    font-size: 16px;
    padding: 14px 0 13px 0;
    border-bottom: 3px solid transparent;
    transition: border-color 0.2s;
}
.navbar-guest-menu a.active,
.navbar-guest-menu a:hover {
    border-bottom: 3px solid #fff;
}
.navbar-guest-auth {
    display: flex;
    align-items: center;
    position: absolute;
    right: 28px;
    top: 4px;
    gap: 12px;
}
.navbar-guest-auth a {
    text-decoration: none;
    font-weight: bold;
    border-radius: 20px;
    padding: 8px 22px;
    font-size: 15px;
    border: none;
    background: #fff;
    color: #0099cc;
    transition: background 0.15s, color 0.15s;
    box-shadow: 0 2px 8px #0099cc22;
    margin: 0;
}
.navbar-guest-auth a + a {
    background: #00bbff;
    color: #fff;
}
.navbar-guest-auth a:hover {
    background: #e6f4fa;
    color: #005f87;
}
.navbar-guest-auth a + a:hover {
    background: #0077a3;
    color: #fff;
}
</style>

<div class="navbar-guest">
    <div class="navbar-guest-menu">
        <a href="/HeThongChamSocThuCung/frontend/dashboards/dashboard.php" <?= ($active_menu ?? '')=='home' ? 'class="active"' : '' ?>>Trang chủ</a>
        <a href="/HeThongChamSocThuCung/frontend/dashboards/about_us.php" <?= ($active_menu ?? '')=='about' ? 'class="active"' : '' ?>>Về chúng tôi</a>
        <a href="/HeThongChamSocThuCung/frontend/products/products_list.php" <?= ($active_menu ?? '')=='products' ? 'class="active"' : '' ?>>Danh mục sản phẩm</a>
        <a href="/HeThongChamSocThuCung/frontend/customer/my_pets.php" <?= ($active_menu ?? '')=='pets' ? 'class="active"' : '' ?>>Thú cưng của tôi</a>
        <a href="/HeThongChamSocThuCung/frontend/appointments/appointments.php" <?= ($active_menu ?? '')=='appointments' ? 'class="active"' : '' ?>>Danh sách lịch hẹn</a>
    </div>
    <div class="navbar-guest-auth">
        <a href="/HeThongChamSocThuCung/frontend/auth/login.php">Đăng nhập</a>
        <a href="/HeThongChamSocThuCung/frontend/auth/register.php">Đăng ký</a>
    </div>
</div>
