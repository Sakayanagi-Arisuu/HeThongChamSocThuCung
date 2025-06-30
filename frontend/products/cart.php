<?php
session_start();
$page_title = "Giỏ hàng";
require_once '../../includes/db.php';
include '../../includes/header.php';
include '../../includes/navbar_customer.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /HeThongChamSocThuCung/frontend/auth/login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Lấy cart_id
$stmt = $conn->prepare("SELECT id FROM carts WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_id = 0;
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $cart_id = $res->fetch_assoc()['id'];
}

$items = [];
if ($cart_id) {
    $stmt = $conn->prepare("
        SELECT ci.id as cart_item_id, ci.quantity, p.*
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.cart_id = ?
    ");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
$total = 0;
?>
<style>
#cart-table {
    margin: 32px 0 0 0;
    width: 100%;
    max-width: 900px;
}
.main-content {
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
}
.sidebar-right {
    min-width: 270px;
    margin-left: 32px;
}
#cart-table table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,160,200,0.10), 0 1.5px 4px rgba(0,0,0,0.08);
    font-size: 16px;
    margin-top: 12px;
}
#cart-table th, #cart-table td {
    border: 1px solid #e0eaf1;
    padding: 10px 12px;
    text-align: center;
}
#cart-table th {
    background: #29b6f6;
    color: #fff;
    font-weight: bold;
    letter-spacing: 0.5px;
}
#cart-table tr:nth-child(even) {
    background: #f4fafd;
}
#cart-table tr:hover {
    background: #e0f7fa;
}
#cart-table h2 {
    color: #009fe3;
    font-size: 28px;
    margin-bottom: 10px;
    margin-top: 0;
}
#cart-table .checkout-btn {
    background: #29b6f6;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    font-weight: 600;
    padding: 10px 28px;
    margin-top: 20px;
    cursor: pointer;
    transition: background 0.16s;
    box-shadow: 0 2px 8px #1aaee2a8;
}
#cart-table .checkout-btn:hover {
    background: #1976d2;
}
.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border: none;
    border-radius: 6px;
    padding: 5px 14px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.12s;
    margin: 0 2px;
}
.action-delete {
    background: #ffe6e9;
    color: #d32f2f;
    border: 1.5px solid #ffbfc3;
}
.action-delete:hover {
    background: #ffcdd2;
    color: #b71c1c;
}
/* Popup checkout */
#checkout-modal {
    display:none; position:fixed; z-index:999; top:0; left:0; width:100vw; height:100vh;
    background:rgba(0,0,0,0.15); align-items:center; justify-content:center;
}
#checkout-modal .checkout-content {
    background:#fff; border-radius:16px; box-shadow:0 2px 8px #0002;
    width:340px; padding:34px 24px 24px 24px; position:relative;
    display:flex; flex-direction:column; align-items:center;
    animation: popupOpen .22s;
}
#checkout-modal h3 {
    margin-top:0; margin-bottom:13px; color:#009fe3;
}
#checkout-modal .qr-label {
    font-size:17px;margin-bottom:13px;
}
#checkout-modal img {
    width:180px; margin-bottom:18px; border-radius:8px; border:1.5px solid #e0eaf1;
}
#checkout-modal .done-btn {
    width:100%;padding:11px;border-radius:8px;border:none;
    background:#29b6f6;color:#fff;font-weight:600;font-size:17px;cursor:pointer;
}
#checkout-modal .done-btn:hover {
    background:#1976d2;
}
#checkout-modal .close-btn {
    position:absolute;top:16px;right:18px;background:none;border:none;
    font-size:26px;color:#999;cursor:pointer;
}
@keyframes popupOpen {
    from {transform:scale(0.95); opacity:0;}
    to {transform:scale(1); opacity:1;}
}
</style>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="main-content">
    <div style="flex: 2;">
        <div id="cart-table">
            <h2>Giỏ hàng của bạn</h2>
            <?php if (empty($items)): ?>
                <p>Chưa có sản phẩm nào trong giỏ!</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Xoá</th>
                    </tr>
                    <?php foreach ($items as $item): 
                        $thanhtien = $item['price'] * $item['quantity'];
                        $total += $thanhtien;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>
                            <img src="<?= htmlspecialchars($item['image']) ?>" style="width:60px; border-radius: 10px; border: 1px solid #eee;">
                        </td>
                        <td><?= number_format($item['price']) ?> đ</td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($thanhtien) ?> đ</td>
                        <td>
                            <form method="post" action="/HeThongChamSocThuCung/backend/api/products/cart/delete_cart_item.php" style="display:inline;">
                                <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                <button type="submit" class="action-btn action-delete" title="Xoá">
                                    <span class="material-icons">delete</span> Xoá
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" align="right"><b>Tổng:</b></td>
                        <td colspan="2"><b><?= number_format($total) ?> đ</b></td>
                    </tr>
                </table>
                <a href="javascript:void(0)" onclick="openCheckout()"><button class="checkout-btn">Thanh toán</button></a>
            <?php endif; ?>
        </div>
    </div>
    <div class="sidebar-right">
        <?php include '../../includes/category.php'; ?>
    </div>
</div>

<!-- Popup thanh toán -->
<div id="checkout-modal">
  <div class="checkout-content">
    <button onclick="closeCheckout()" class="close-btn">&times;</button>
    <h3>Thanh toán đơn hàng</h3>
    <div class="qr-label">Vui lòng quét mã QR để thanh toán</div>
    <img src="/HeThongChamSocThuCung/images/qr_code.png" alt="QR code">
    <button onclick="doneCheckout()" class="done-btn">Đã thanh toán</button>
  </div>
</div>

<script>
function openCheckout() {
    document.getElementById('checkout-modal').style.display = 'flex';
}
function closeCheckout() {
    document.getElementById('checkout-modal').style.display = 'none';
}
function doneCheckout() {
    // Gọi API backend xử lý thanh toán (hoặc demo alert)
    fetch('/HeThongChamSocThuCung/backend/api/products/cart/checkout_cart.php', {
        method: 'POST'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Thanh toán thành công!');
            window.location.reload();
        } else {
            alert(data.error || 'Lỗi!');
        }
        closeCheckout();
    })
    .catch(() => {
        alert('Có lỗi kết nối server!');
        closeCheckout();
    });
}
</script>

<?php include '../../includes/footer.php'; ?>
