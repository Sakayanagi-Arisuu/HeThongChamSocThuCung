<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký - Pet Care Services</title>
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
        .register-container { max-width: 320px; margin: 60px auto; background: url('https://i.imgur.com/h35JdL3.png') no-repeat center; background-size: cover; padding: 40px 20px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); position: relative; }
        .register-container::before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.85); border-radius: 12px; }
        .register-form { position: relative; z-index: 1; }
        .register-form h2 { text-align: center; margin-bottom: 20px; color: #0077a3; }
        .register-form input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 20px; font-size: 14px; }
        .register-form a { display: block; font-size: 12px; text-align: right; margin-bottom: 10px; text-decoration: none; color: #0077a3; }
        .register-form button { width: 100%; padding: 10px; border: none; border-radius: 20px; background-color: #0077a3; color: white; font-weight: bold; font-size: 16px; cursor: pointer; }
        .register-form button:hover { background-color: #005f87; }
        .register-form .message {
            margin-bottom: 12px;
            border-radius: 8px;
            padding: 9px 0;
            font-size: 15px;
            font-weight: bold;
        }
        .register-form .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb;}
        .register-form .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;}
    </style>
</head>
<body>
    <?php
    include __DIR__ . '/../../includes/header.php';
    include __DIR__ . '/../../includes/navbar_guess.php';
    ?>

    <div class="register-container">
        <form class="register-form" id="registerForm" autocomplete="off">
            <h2>Đăng ký</h2>
            <div id="message"></div>
            <input type="text" name="full_name" placeholder="Họ và tên" required>
            <input type="text" name="contact_info" placeholder="Liên hệ (SĐT hoặc Email)" required>
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng ký</button>
            <a href="login.php">Đã có tài khoản? Đăng nhập</a>
        </form>
    </div>

    <script>
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        // Gửi dữ liệu tới API backend xử lý đăng ký
        let res = await fetch('/HeThongChamSocThuCung/backend/api/auth/register.php', {
            method: 'POST',
            body: formData
        });

        let text = await res.text();
        let msg = document.getElementById('message');
        msg.className = 'message';
        if (res.ok) {
            // Nếu trả về json có thể parse
            try {
                let data = JSON.parse(text);
                if(data.success) {
                    msg.classList.add('success');
                    msg.innerText = data.message || "Đăng ký thành công!";
                    form.reset();
                } else {
                    msg.classList.add('error');
                    msg.innerText = data.message || "Đăng ký thất bại!";
                }
            } catch {
                // Nếu trả về plain text
                msg.classList.add('success');
                msg.innerText = text;
                form.reset();
            }
        } else {
            msg.classList.add('error');
            msg.innerText = text || "Đăng ký thất bại!";
        }
    });
    </script>
</body>
</html>
