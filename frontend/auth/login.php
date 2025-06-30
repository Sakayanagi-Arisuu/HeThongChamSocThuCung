<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Pet Care Services</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f9f9f9; }
        .header { background: white; display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-bottom: 2px solid #0077a3; }
        .header .logo { display: flex; align-items: center; }
        .header .logo img { height: 40px; margin-right: 10px; }
        .header .logo span { font-weight: bold; color: #0077a3; font-size: 20px; }
        .header .top-info { text-align: right; font-size: 13px; color: #333; }
        .navbar { background: #0077a3; padding: 10px 20px; }
        .navbar a { color: white; text-decoration: none; margin-right: 15px; font-weight: bold; }
        .login-error {
            position: relative;
            z-index: 3;
            color: #d81b60;
            background: #fff;
            border: 1px solid #ffc7c7;
            border-radius: 8px;
            margin: 0 auto 18px auto;
            padding: 12px 15px 8px 15px;
            text-align: center;
            font-weight: bold;
            max-width: 340px;
            box-shadow: 0 1px 8px #0001;
            font-size: 16px;
        }
        .login-container { 
            max-width: 340px; 
            margin: 40px auto; 
            background: url('/HeThongChamSocThuCung/images/login.jpg') no-repeat center;
            background-size: cover; 
            padding: 45px 25px 35px 25px; 
            border-radius: 14px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.10); 
            position: relative; 
        }
        .login-container::before { 
            content: ""; 
            position: absolute; 
            top: 0; left: 0; right: 0; bottom: 0; 
            background: rgba(255,255,255,0.85); 
            border-radius: 14px; 
            z-index: 1;
        }
        .login-form { position: relative; z-index: 2; }
        .login-form h2 { 
            text-align: center; 
            margin-bottom: 20px; 
            color: #0077a3; 
            font-size: 28px;
            font-weight: bold;
        }
        .login-form input { 
            width: 100%; 
            padding: 10px 18px; 
            margin-bottom: 17px; 
            border: 1px solid #b4daee; 
            border-radius: 22px; 
            font-size: 15px; 
            background: #eaf6fa;
        }
        .login-form a { 
            display: block; 
            font-size: 13px; 
            text-align: right; 
            margin-bottom: 14px; 
            text-decoration: none; 
            color: #0077a3; 
        }
        .login-form button { 
            width: 100%; 
            padding: 11px 0; 
            border: none; 
            border-radius: 22px; 
            background-color: #0086c1; 
            color: white; 
            font-weight: bold; 
            font-size: 18px; 
            cursor: pointer; 
            margin-top: 8px;
            transition: background 0.18s;
        }
        .login-form button:hover { background-color: #005f87; }
    </style>
</head>
<body>
    <?php
    include __DIR__ . '/../../includes/header.php';
    include __DIR__ . '/../../includes/navbar_guest.php';
    ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="login-error">Sai tên đăng nhập hoặc mật khẩu!</div>
    <?php endif; ?>

    <div class="login-container">
        <form class="login-form" method="POST" action="/HeThongChamSocThuCung/backend/api/auth/login.php">
            <h2>Đăng nhập</h2>
            <input type="text" name="username" placeholder="Username" required autocomplete="username">
            <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
            <a href="#">Quên mật khẩu?</a>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
<?php

include '../../includes/footer.php';
?>
</html>
