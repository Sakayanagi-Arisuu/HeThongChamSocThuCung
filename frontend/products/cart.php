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

// Lấy thông tin user
$user_stmt = $conn->prepare("SELECT full_name, contact_info, address FROM users WHERE id=?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_info = $user_stmt->get_result()->fetch_assoc();

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
<!-- css -->
<link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/products/cart.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Truyền user_info sang JS -->
<script>
var USER_INFO = <?= json_encode($user_info) ?>;
</script>

<div class="cart-page">
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

    <!-- Popup thanh toán: Đặt vào trong cart-page để CSS vẫn đúng -->
    <div id="checkout-modal" style="display:none;">
        <div class="checkout-content">
            <button onclick="closeCheckout()" class="close-btn">&times;</button>
            <h3>Thông tin nhận hàng</h3>
            <label for="checkout-contact" style="font-weight:bold; width:100%; text-align:left;">Email/SĐT liên hệ:</label>
            <input type="text" id="checkout-contact" readonly>
            <label for="checkout-address" style="font-weight:bold; width:100%; text-align:left;">Địa chỉ nhận hàng:</label>
            <input type="text" id="checkout-address">
            <div class="qr-label">Vui lòng quét mã QR để thanh toán</div>
            <img src="/HeThongChamSocThuCung/assets/images/qr_code.png" alt="QR code">
            <button onclick="doneCheckout()" class="done-btn">Đã thanh toán</button>
        </div>
    </div>
</div>

<script src="/HeThongChamSocThuCung/assets/js/products/cart.js"></script>

<?php include '../../includes/footer.php'; ?>
