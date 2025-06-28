<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}

$page_title = "Thống kê doanh thu";
include '../../includes/header.php';
include '../../includes/navbar_admin.php';

// Lấy tham số lọc
$type = $_GET['type'] ?? 'date'; // 'date', 'month', 'year'
$date_from = $_GET['from'] ?? '';
$date_to = $_GET['to'] ?? '';

switch ($type) {
    case 'month': 
        $groupby_orders = "DATE_FORMAT(created_at, '%Y-%m')";
        $groupby_apps = "DATE_FORMAT(paid_at, '%Y-%m')";
        $label = "Tháng";
        $input_type = 'month';
        break;
    case 'year':
        $groupby_orders = "YEAR(created_at)";
        $groupby_apps = "YEAR(paid_at)";
        $label = "Năm";
        $input_type = 'number';
        break;
    default:
        $groupby_orders = "DATE(created_at)";
        $groupby_apps = "DATE(paid_at)";
        $label = "Ngày";
        $input_type = 'date';
        break;
}

$where_order = "WHERE payment_status = 'Đã thanh toán'";
$where_app = "WHERE payment_status = 'Đã thanh toán'";

if ($date_from) {
    if ($type == 'month')      $where_order .= " AND DATE_FORMAT(created_at, '%Y-%m') >= '$date_from'";
    elseif ($type == 'year')   $where_order .= " AND YEAR(created_at) >= '$date_from'";
    else                       $where_order .= " AND DATE(created_at) >= '$date_from'";
}
if ($date_to) {
    if ($type == 'month')      $where_order .= " AND DATE_FORMAT(created_at, '%Y-%m') <= '$date_to'";
    elseif ($type == 'year')   $where_order .= " AND YEAR(created_at) <= '$date_to'";
    else                       $where_order .= " AND DATE(created_at) <= '$date_to'";
}

if ($date_from) {
    if ($type == 'month')      $where_app .= " AND DATE_FORMAT(paid_at, '%Y-%m') >= '$date_from'";
    elseif ($type == 'year')   $where_app .= " AND YEAR(paid_at) >= '$date_from'";
    else                       $where_app .= " AND DATE(paid_at) >= '$date_from'";
}
if ($date_to) {
    if ($type == 'month')      $where_app .= " AND DATE_FORMAT(paid_at, '%Y-%m') <= '$date_to'";
    elseif ($type == 'year')   $where_app .= " AND YEAR(paid_at) <= '$date_to'";
    else                       $where_app .= " AND DATE(paid_at) <= '$date_to'";
}

$sql_orders = "
    SELECT $groupby_orders AS period, SUM(total_amount) AS doanhthu, COUNT(*) AS sodon
    FROM orders
    $where_order
    GROUP BY period
";
$res_orders = $conn->query($sql_orders);
$order_revenue = [];
while ($row = $res_orders->fetch_assoc()) {
    $order_revenue[$row['period']] = [
        'doanhthu' => (float)$row['doanhthu'],
        'sodon' => (int)$row['sodon']
    ];
}

$sql_apps = "
    SELECT $groupby_apps AS period, SUM(fee) AS doanhthu, COUNT(*) AS sldon
    FROM appointments
    $where_app
    GROUP BY period
";
$res_apps = $conn->query($sql_apps);
$appt_revenue = [];
while ($row = $res_apps->fetch_assoc()) {
    $appt_revenue[$row['period']] = [
        'doanhthu' => (float)$row['doanhthu'],
        'sldon' => (int)$row['sldon']
    ];
}

$all_periods = array_unique(array_merge(array_keys($order_revenue), array_keys($appt_revenue)));
rsort($all_periods);

$total_orders = 0; $total_orders_revenue = 0;
$total_appt = 0; $total_appt_revenue = 0;
$rows = [];
foreach ($all_periods as $period) {
    $doanhthu_orders = $order_revenue[$period]['doanhthu'] ?? 0;
    $sodon = $order_revenue[$period]['sodon'] ?? 0;
    $doanhthu_appt = $appt_revenue[$period]['doanhthu'] ?? 0;
    $sldon = $appt_revenue[$period]['sldon'] ?? 0;

    $rows[] = [
        'period' => $period,
        'orders' => $sodon,
        'orders_revenue' => $doanhthu_orders,
        'appt' => $sldon,
        'appt_revenue' => $doanhthu_appt,
        'tong' => $doanhthu_orders + $doanhthu_appt,
        'tong_don' => $sodon + $sldon
    ];
    $total_orders += $sodon;
    $total_orders_revenue += $doanhthu_orders;
    $total_appt += $sldon;
    $total_appt_revenue += $doanhthu_appt;
}
?>
<style>
#revenue-table {
    margin: 38px 0 0 0;
    width: 100%;
    max-width: 980px;
}
#revenue-table table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,160,200,0.10);
    font-size: 16px;
    margin-top: 12px;
}
#revenue-table th, #revenue-table td {
    border: 1px solid #e0eaf1;
    padding: 10px 12px;
    text-align: center;
}
#revenue-table th {
    background: #29b6f6;
    color: #fff;
    font-weight: bold;
}
#revenue-table tr:nth-child(even) { background: #f4fafd;}
#revenue-table h2 { color: #009fe3; font-size: 28px; margin-bottom: 16px;}
.main-content {
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    min-height: 70vh;
}
.sidebar-right {
    min-width: 270px;
    margin-left: 32px;
}
</style>

<div class="main-content">
    <div style="flex: 2;">
        <div style="margin-bottom:18px;">
            <form method="get" style="display:inline;">
                <label for="type" style="font-weight:bold;font-size:17px;">Xem theo: </label>
                <select id="type" name="type" onchange="this.form.submit()" style="padding:6px 13px;border-radius:6px;font-size:16px;">
                    <option value="date" <?= $type=='date'?'selected':'' ?>>Ngày</option>
                    <option value="month" <?= $type=='month'?'selected':'' ?>>Tháng</option>
                    <option value="year" <?= $type=='year'?'selected':'' ?>>Năm</option>
                </select>
                <label style="margin-left:24px;">Từ:</label>
                <input type="<?= $input_type ?>" name="from" value="<?= htmlspecialchars($date_from) ?>" style="padding:5px 10px;border-radius:6px;font-size:15px;">
                <label>đến:</label>
                <input type="<?= $input_type ?>" name="to" value="<?= htmlspecialchars($date_to) ?>" style="padding:5px 10px;border-radius:6px;font-size:15px;">
                <button type="submit" style="padding:6px 18px;border-radius:6px;margin-left:8px;font-size:15px;">Lọc</button>
            </form>
        </div>
        <div id="revenue-table">
            <h2>Thống kê doanh thu theo <?= $label ?></h2>
            <table>
                <tr>
                    <th><?= $label ?></th>
                    <th>Đơn hàng<br>(sp)</th>
                    <th>Doanh thu<br>đơn hàng</th>
                    <th>Đơn khám</th>
                    <th>Doanh thu<br>khám bệnh</th>
                    <th>Tổng số đơn</th>
                    <th>Tổng doanh thu</th>
                </tr>
                <?php if (empty($rows)): ?>
                <tr><td colspan="7">Chưa có dữ liệu đơn hàng/lịch khám!</td></tr>
                <?php else: foreach ($rows as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['period']) ?></td>
                    <td><?= (int)$r['orders'] ?></td>
                    <td><?= number_format($r['orders_revenue']) ?> đ</td>
                    <td><?= (int)$r['appt'] ?></td>
                    <td><?= number_format($r['appt_revenue']) ?> đ</td>
                    <td><?= (int)$r['tong_don'] ?></td>
                    <td><?= number_format($r['tong']) ?> đ</td>
                </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold;background:#e1f5fe;">
                    <td>Tổng</td>
                    <td><?= (int)$total_orders ?></td>
                    <td><?= number_format($total_orders_revenue) ?> đ</td>
                    <td><?= (int)$total_appt ?></td>
                    <td><?= number_format($total_appt_revenue) ?> đ</td>
                    <td><?= (int)($total_orders+$total_appt) ?></td>
                    <td><?= number_format($total_orders_revenue+$total_appt_revenue) ?> đ</td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <div class="sidebar-right">
        <?php include '../../includes/category.php'; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
