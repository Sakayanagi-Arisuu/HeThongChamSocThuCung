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