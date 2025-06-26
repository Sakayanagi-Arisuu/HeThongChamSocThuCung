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
        .login-container { max-width: 300px; margin: 60px auto; background: url('https://i.imgur.com/h35JdL3.png') no-repeat center; background-size: cover; padding: 40px 20px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); position: relative; }
        .login-container::before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.85); border-radius: 12px; }
        .login-form { position: relative; z-index: 1; }
        .login-form h2 { text-align: center; margin-bottom: 20px; color: #0077a3; }
        .login-form input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 20px; font-size: 14px; }
        .login-form a { display: block; font-size: 12px; text-align: right; margin-bottom: 10px; text-decoration: none; color: #0077a3; }
        .login-form button { width: 100%; padding: 10px; border: none; border-radius: 20px; background-color: #0077a3; color: white; font-weight: bold; font-size: 16px; cursor: pointer; }
        .login-form button:hover { background-color: #005f87; }
    </style>
</head>
<body>
    <?php
    include __DIR__ . '/../../includes/header.php';
    include __DIR__ . '/../../includes/navbar_guess.php';
    ?>

    <div class="login-container">
        <form class="login-form" method="POST" action="/HeThongChamSocThuCung/backend/api/auth/login.php">
            <h2>Đăng nhập</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <a href="#">Quên mật khẩu?</a>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
