<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?? 'Pet Care Services'; ?></title>
    <style>
        body {font-family: Arial, sans-serif; background: #f9f9f9;}
        .header {background: white; display: flex; justify-content: space-between; align-items: center; padding: 10px 30px; border-bottom: 2px solid #0077a3;}
        .header .logo {display: flex; align-items: center;}
        .header .logo img {height: 45px; margin-right: 12px;}
        .header .logo span {font-weight: bold; color: #0077a3; font-size: 28px; line-height: 32px;}
        .header .top-info {font-size: 14px; color: #333; text-align: right;}
        .header .hotline {color: #e74c3c; font-weight: bold; font-size: 18px;}
        .header .datlich {background: #0099cc; color: white; padding: 8px 16px; border-radius: 22px; margin-left: 20px; text-decoration: none; font-weight: bold;}
        .navbar {background: #0099cc; padding: 10px 30px;}
        .navbar a {color: white; text-decoration: none; margin-right: 18px; font-weight: bold; font-size: 15px;}
        .main-content {display: flex; margin: 40px 7% 20px 7%; min-height: 500px;}
        .left-content {flex: 2; margin-right: 45px;}
        .right-content {flex: 1; background: #eaf6ff; border-radius: 10px; padding: 15px 20px; min-width: 250px;}
        .category-box {background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 2px 7px rgba(0,0,0,0.05);}
        .category-box h3 {margin-bottom: 16px; background: #33b7ff; color: #fff; font-size: 18px; padding: 7px 14px; border-radius: 6px;}
        .category-list {list-style: none; padding-left: 0; margin: 0;}
        .category-list li {padding: 8px 4px; border-bottom: 1px solid #ececec; color: #f49313; font-weight: bold; font-size: 15px;}
        .category-list li:last-child {border-bottom: none;}
        .category-list span {color: #a5a5a5; float: right;}
        .appointment-title {font-size: 28px; font-weight: bold; margin-bottom: 10px;}
        .desc {font-size: 17px; margin-bottom: 10px;}
        .desc .hotline {color: #e74c3c; font-weight: bold;}
        .desc .online {font-weight: bold;}
        .desc-green {color: #09b40b; font-weight: bold; margin-bottom: 8px;}
        .form-book {background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); margin-top: 15px;}
        .form-book label {font-weight: bold; display: block; margin-bottom: 4px;}
        .form-book input, .form-book select, .form-book textarea {width: 100%; padding: 9px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px; margin-bottom: 16px; background: #f9f9f9;}
        .form-book input[type='date'], .form-book input[type='datetime-local'] {padding-right: 0;}
        .form-book button {width: 100%; background: #0099cc; color: #fff; font-weight: bold; border: none; padding: 12px; font-size: 18px; border-radius: 8px; cursor: pointer; transition: background 0.2s;}
        .form-book button:hover {background: #0077a3;}
        @media (max-width: 900px) {.main-content { flex-direction: column; margin: 30px 3%; } .left-content { margin-right: 0; } .right-content { margin-top: 25px; }}
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="https://i.imgur.com/G4lY3UT.png" alt="logo">
            <span>PET CARE SERVICES</span>
        </div>
        <div>
            <div class="top-info">
                <span style="margin-right: 30px;">
                    <img src="https://img.icons8.com/material-sharp/20/0099cc/phone.png" style="vertical-align: middle;">
                    Làm việc từ T2 - CN<br>
                    <span style="font-weight:bold; font-size:15px;">7:00 AM - 7:00 PM</span>
                </span>
                <span>
                    <img src="https://img.icons8.com/ios-filled/22/0099cc/calendar--v1.png" style="vertical-align: middle;">
                    <a href="#" class="datlich">ĐẶT LỊCH HẸN</a>
                </span>
                <span>
                    <a href="#" class="datlich" style="background:#33b7ff;">
                        GIỎ HÀNG / 0đ
                        <img src="https://img.icons8.com/ios-glyphs/18/ffffff/shopping-cart.png" style="vertical-align: middle;">
                    </a>
                </span>
            </div>
        </div>
    </div>
    <!-- <div class="navbar">
    <a href="#">Trang chủ</a>
    <a href="#">Về chúng tôi</a>
    <a href="#">Bệnh viện thú y</a>
    <a href="#">Danh mục sản phẩm</a>
    <div style="float:right;display:inline-block;margin-left:20px;position:relative;">
        <img src="<?= isset($_SESSION['avatar']) ? $_SESSION['avatar'] : 'https://ui-avatars.com/api/?name=User' ?>" alt="avatar" style="width:35px;height:35px;border-radius:50%;border:2px solid #fff;vertical-align:middle;cursor:pointer;" onclick="document.getElementById('userMenu').style.display='block'">
        <span style="color:#fff;font-weight:bold;margin-left:6px;">
            <?= isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Tài khoản' ?>
        </span>
        <div id="userMenu" style="display:none;position:absolute;right:0;top:40px;background:#fff;box-shadow:0 4px 16px #0002;border-radius:8px;z-index:99;">
            <a href="/HeThongChamSocThuCung/frontend/user/profile.php" style="display:block;padding:10px 16px;text-decoration:none;color:#0099cc;">Thông tin cá nhân</a>
            <a href="/HeThongChamSocThuCung/backend/api/auth/logout.php" style="display:block;padding:10px 16px;text-decoration:none;color:#e74c3c;">Đăng xuất</a>
        </div>
    </div> -->
</div>
<script>
document.onclick = function(e) {
    var menu = document.getElementById('userMenu');
    if (menu && e.target.alt !== 'avatar') menu.style.display = 'none';
}
</script>

