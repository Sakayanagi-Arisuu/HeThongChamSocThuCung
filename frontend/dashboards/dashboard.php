<?php
session_start();

$page_title = "Pet Care Services";
include '../../includes/header.php';

// Lấy vai trò người dùng (mặc định là guest nếu chưa đăng nhập)
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';

// Include navbar theo vai trò
switch ($role) {
    case 'customer':
        include '../../includes/navbar_customer.php';
        break;
    case 'admin':
        include '../../includes/navbar_admin.php';
        break;
    case 'doctor':
        include '../../includes/navbar_doctor.php';
        break;
    default:
        include '../../includes/navbar_guest.php';
}

?>
<!-- css -->
<link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/dashboards/dashboard.css">

<div class="section">
    <!-- HERO -->
    <div class="hero-section">
        <div class="hero-text">
            <div class="hero-label">PET SERVICE</div>
            <div class="hero-title">
                Dịch Vụ <span>Thú Cưng</span>
            </div>
            <div class="hero-desc">Hàng Đầu tại Hồ Chí Minh</div>
            <div class="hero-btn-group">
                <a href="#services" class="hero-btn">XEM THÊM</a>
                <a href="/HeThongChamSocThuCung/frontend/appointments/book_appointment.php" class="hero-btn hero-btn-outline">ONLINE BOOKING</a>
            </div>
        </div>
        <div class="hero-img">
            <img src="../../assets/images/anh2.jpg" alt="Pet Service">
        </div>
    </div>
</div>

<!-- SERVICES SECTION -->
<div class="section services-section" id="services">
    <div class="services-title">Dịch vụ nổi bật</div>
    <div class="service-cards">
        <div class="service-card">
            <span class="service-icon">✂️</span>
            <h3>GROOMING</h3>
            <p>Chúng tôi biết cách làm thế nào để thú cưng của bạn trở nên đẳng cấp và tinh hơn. Dịch vụ cắt tỉa lông giúp bé trở thành phiên bản hoàn hảo nhất.</p>
            <!-- <button class="card-btn">Xem Thêm</button> -->
        </div>
        <div class="service-card">
            <span class="service-icon">🐶</span>
            <h3>SHOP</h3>
            <p>Cùng với hơn 3.000 khách hàng tin tưởng, chúng tôi cung cấp sản phẩm, phụ kiện đa dạng cho thú cưng của bạn.</p>
            <!-- <button class="card-btn">Xem Thêm</button> -->
        </div>
        <div class="service-card">
            <span class="service-icon">🏨</span>
            <h3>HOSPITAL</h3>
            <p>Mọi thú cưng mới khi đến với chúng tôi đều được chăm sóc đặc biệt bởi đội ngũ nhân viên nhiều kinh nghiệm.</p>
            <!-- <button class="card-btn">Xem Thêm</button> -->
        </div>
    </div>
</div>
</div>

<?php include '../../includes/footer.php'; ?>
