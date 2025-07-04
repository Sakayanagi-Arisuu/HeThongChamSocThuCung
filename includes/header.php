<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = $_SESSION['role'] ?? 'guest';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?? 'Pet Care Services'; ?></title>
    <style>
        body {font-family: Arial, sans-serif; background: #f9f9f9;}
        .header {
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 30px;
            border-bottom: 2px solid #0077a3;
        }
        .header .logo {display: flex; align-items: center;}
        .header .logo img {height: 45px; margin-right: 12px;}
        .header .logo span {
            font-weight: bold; color: #0077a3;
            font-size: 28px; line-height: 32px;
        }

        .header .top-info {
            font-size: 14px;
            color: #333;
            text-align: right;
            display: flex;
            align-items: center;
            gap: 24px; /* Khoảng cách giữa các mục */
        }
        .header .top-info span {
            display: flex;
            align-items: center;
            gap: 7px; /* Khoảng cách giữa icon và text */
        }
        .header .top-info .worktime {
            flex-direction: row;
            gap: 7px;
        }
        .header .top-info .worktext {
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }

        .header .hotline {color: #e74c3c; font-weight: bold; font-size: 18px;}
        .header .datlich {
            background: #0099cc;
            color: white;
            padding: 8px 16px;
            border-radius: 22px;
            margin-left: 0;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .navbar {background: #0099cc; padding: 10px 30px;}
        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 18px;
            font-weight: bold;
            font-size: 15px;
        }
        .main-content {display: flex; margin: 40px 7% 20px 7%; min-height: 500px;}
        .left-content {flex: 2; margin-right: 45px;}
        .right-content {
            flex: 1;
            background: #eaf6ff;
            border-radius: 10px;
            padding: 15px 20px;
            min-width: 250px;
        }
        .category-box {
            background: #fff;
            border-radius: 8px;
            padding: 12px;
            box-shadow: 0 2px 7px rgba(0,0,0,0.05);
        }
        .category-box h3 {
            margin-bottom: 16px;
            background: #33b7ff;
            color: #fff;
            font-size: 18px;
            padding: 7px 14px;
            border-radius: 6px;
        }
        .category-list {list-style: none; padding-left: 0; margin: 0;}
        .category-list li {
            padding: 8px 4px;
            border-bottom: 1px solid #ececec;
            color: #f49313;
            font-weight: bold;
            font-size: 15px;
        }
        .category-list li:last-child {border-bottom: none;}
        .category-list span {color: #a5a5a5; float: right;}
        .appointment-title {font-size: 28px; font-weight: bold; margin-bottom: 10px;}
        .desc {font-size: 17px; margin-bottom: 10px;}
        .desc .hotline {color: #e74c3c; font-weight: bold;}
        .desc .online {font-weight: bold;}
        .desc-green {color: #09b40b; font-weight: bold; margin-bottom: 8px;}
        .form-book {
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            margin-top: 15px;
        }
        .form-book label {font-weight: bold; display: block; margin-bottom: 4px;}
        .form-book input, .form-book select, .form-book textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            margin-bottom: 16px;
            background: #f9f9f9;
        }
        .form-book input[type='date'], .form-book input[type='datetime-local'] {padding-right: 0;}
        .form-book button {
            width: 100%;
            background: #0099cc;
            color: #fff;
            font-weight: bold;
            border: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .form-book button:hover {background: #0077a3;}
        @media (max-width: 900px) {
            .main-content { flex-direction: column; margin: 30px 3%; }
            .left-content { margin-right: 0; }
            .right-content { margin-top: 25px; }
            .header { flex-direction: column; align-items: flex-start; }
            .header .top-info { flex-direction: column; gap: 10px; margin-top: 10px; }
        }
    </style>
</head>
<body>
    <div class="header">
    <div class="logo">
        <img src="\HeThongChamSocThuCung\assets\images\anh.jpg" alt="logo">
        <span>PET CARE SERVICES</span>
    </div>
    <div class="top-info">
        <span class="worktime" style="align-items: center;">
            <!-- Dịch icon điện thoại sang trái bằng margin-right -->
            <img src="https://img.icons8.com/material-sharp/20/0099cc/phone.png" alt="phone" style="margin-right: 5px; margin-left: -7px;">
            <!-- Thêm số điện thoại bên cạnh icon -->
            <span style="font-weight:bold; color:#e74c3c; font-size:17px; margin-right: 13px;">099998888</span>
            <span class="worktext">
                Làm việc từ T2 - CN
                <span style="font-weight:bold; font-size:15px;">7:00 AM - 7:00 PM</span>
            </span>
        </span>
        <?php if ($role == 'customer' || $role == 'guest'): ?>
        <span>
            <img src="https://img.icons8.com/ios-filled/22/0099cc/calendar--v1.png" style="vertical-align: middle;">
            <a href="\HeThongChamSocThuCung\frontend\appointments\book_appointment.php" class="datlich">ĐẶT LỊCH HẸN</a>
        </span>
        <?php endif; ?>
        <?php if ($role == 'customer' || $role == 'doctor'): ?>
        <span>
            <a href="\HeThongChamSocThuCung\frontend\products\cart.php" class="datlich" style="background:#33b7ff;">
                GIỎ HÀNG
                <img src="https://img.icons8.com/ios-glyphs/18/ffffff/shopping-cart.png" style="vertical-align: middle;">
            </a>
        </span>
        <?php endif; ?>
    </div>
</div>
    <script>
    document.onclick = function(e) {
        var menu = document.getElementById('userMenu');
        if (menu && e.target.alt !== 'avatar') menu.style.display = 'none';
    }
    </script>
</body>
</html>
