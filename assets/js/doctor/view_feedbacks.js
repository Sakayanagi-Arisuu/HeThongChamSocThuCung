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