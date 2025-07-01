<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$page_title = "Đánh giá của khách hàng";
include '../../includes/header.php';
include '../../includes/navbar_doctor.php';
?>
<style>
#feedback-table { margin:32px auto; max-width:850px;}
#feedback-table table { width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 12px #0002;}
#feedback-table th, #feedback-table td { padding:10px 12px; border:1px solid #e2eaf2; text-align:left;}
#feedback-table th { background:#29b6f6; color:#fff; }
.rating-stars { color:#fbc02d; font-size:18px; }
<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
.main-content, #feedback-table {
    flex: 1;
}
footer {
    width: 100%;
}

</style>


</style>

<div id="feedback-table"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/HeThongChamSocThuCung/backend/api/doctor/feedbacks/api_get_feedbacks.php')
    .then(res=>res.json())
    .then(data=>{
        let html = `
            <h2>Đánh giá của khách hàng</h2>
            <table>
                <tr>
                    <th>Khách hàng</th>
                    <th>Thời gian hẹn</th>
                    <th>Số sao</th>
                    <th>Nhận xét</th>
                    <th>Ngày đánh giá</th>
                </tr>
        `;
        if (!data || data.length === 0) {
            html += `<tr><td colspan="5" style="color:#888;">Chưa có đánh giá nào!</td></tr>`;
        } else {
            data.forEach(row=>{
                html += `
                    <tr>
                        <td>${row.customer_name||''}</td>
                        <td>${row.schedule_time||''}</td>
                        <td>
                            <span class="rating-stars">
                                ${'★'.repeat(row.rating)}${'☆'.repeat(5-row.rating)}
                            </span>
                        </td>
                        <td>${row.comment||''}</td>
                        <td>${row.created_at}</td>
                    </tr>
                `;
            });
        }
        html += `</table>`;
        document.getElementById('feedback-table').innerHTML = html;
    });
});
</script>
<?php include '../../includes/footer.php'; ?>
