<style>
.navbar-guess {
    background: #0077a3;
    padding: 10px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 48px;
    position: relative;
}
.navbar-guess-menu {
    display: flex;
    align-items: center;
    flex: 1;
    justify-content: space-evenly;  /* CHÍNH LÀ DÒNG NÀY */
}
.navbar-guess-menu a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    padding: 12px 26px;  /* Tăng padding ngang cho đều */
    border-bottom: 3px solid transparent;
    transition: border-color 0.2s;
    text-align: center;
    flex: 1; /* Mỗi menu chiếm đều không gian */
}
.navbar-guess-menu a.active,
.navbar-guess-menu a:hover {
    border-bottom: 3px solid #fff;
}
.navbar-guess-auth {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-left: 0px; /* cách ra so với menu */
}
.navbar-guess-auth a {
    text-decoration: none;
    font-weight: bold;
    border-radius: 20px;
    padding: 8px 22px;
    font-size: 15px;
    border: none;
    background: #fff;
    color: #0077a3;
    transition: background 0.15s, color 0.15s;
    box-shadow: 0 2px 8px #0077a322;
}
.navbar-guess-auth a + a {
    background: #00bbff;
    color: #fff;
}
.navbar-guess-auth a:hover {
    background: #e6f4fa;
    color: #005f87;
}
.navbar-guess-auth a + a:hover {
    background: #0077a3;
    color: #fff;
}
</style>


<div class="navbar-guess">
    <div class="navbar-guess-menu">
        <a href="/HeThongChamSocThuCung/index.php" <?= ($active_menu ?? '')=='home' ? 'class="active"' : '' ?>>Trang chủ</a>
        <a href="#" <?= ($active_menu ?? '')=='about' ? 'class="active"' : '' ?>>Về chúng tôi</a>
        <a href="#" <?= ($active_menu ?? '')=='hospital' ? 'class="active"' : '' ?>>Bệnh viện thú y</a>
        <a href="#" <?= ($active_menu ?? '')=='products' ? 'class="active"' : '' ?>>Danh mục sản phẩm</a>
    </div>
    <div class="navbar-guess-auth">
        <a href="/HeThongChamSocThuCung/frontend/auth/login.php">Đăng nhập</a>
        <a href="/HeThongChamSocThuCung/frontend/auth/register.php" style="background: #00bbff; color:#fff;">Đăng ký</a>
    </div>
</div>
