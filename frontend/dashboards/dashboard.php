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

<style>
body {
    background: #fff;
}
.section {
    width: 100%;
    margin: 0 auto;
    max-width: 1300px;
    padding: 0 2vw;
}
.hero-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 38px;
    min-height: 480px;
    padding: 50px 0 38px 0;
}
.hero-text {
    flex: 1 1 55%;
    max-width: 560px;
}
.hero-label {
    font-size: 1rem;
    font-weight: 600;
    color: #2950a0;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    margin-bottom: 18px;
}
.hero-title {
    font-size: 3.1rem;
    font-weight: 800;
    color: #21213a;
    line-height: 1.07;
    margin-bottom: 15px;
    font-family: 'Montserrat', Arial, sans-serif;
    letter-spacing: 1.1px;
    text-shadow: 2px 4px 9px #e8e9fa28;
}
.hero-title span {
    color: #2463ff;
    font-style: italic;
    letter-spacing: 2px;
    font-weight: 900;
}
.hero-desc {
    font-size: 1.22rem;
    font-weight: 600;
    color: #1d2333;
    margin-bottom: 25px;
    text-transform: uppercase;
}
.hero-btn-group {
    display: flex;
    gap: 16px;
}
.hero-btn {
    display: inline-block;
    padding: 13px 33px;
    font-size: 1rem;
    font-weight: 700;
    border-radius: 32px;
    border: none;
    cursor: pointer;
    background: #2463ff;
    color: #fff;
    box-shadow: 0 2px 10px #cce8ff1c;
    transition: background 0.22s, color 0.22s, box-shadow 0.2s;
}
.hero-btn:hover {
    background: #1a41b5;
}
.hero-btn-outline {
    background: #fff;
    color: #2463ff;
    border: 2px solid #2463ff;
    box-shadow: none;
}
.hero-btn-outline:hover {
    background: #f7fbff;
    color: #15377c;
}

/* CHỈNH TO ẢNH HERO */
.hero-img {
    flex: 1 1 45%;
    min-width: 320px;
    max-width: 650px;
    text-align: right;
    display: flex;
    justify-content: flex-end;
    align-items: center;
}
.hero-img img {
    max-width: 95%;
    width: 550px;
    height: auto;
    border-radius: 18px;
    filter: drop-shadow(0 10px 38px #bbe7ff42);
    background: #fff;
}

@media (max-width: 1200px) {
    .hero-img img { width: 430px; }
}
@media (max-width: 950px) {
    .hero-section { flex-direction: column-reverse; text-align:center; }
    .hero-img { text-align: center; margin-bottom: 10px; justify-content: center;}
    .hero-img img { width: 340px; }
    .hero-text { max-width: 100%; }
}
.services-section {
    padding: 34px 0 40px 0;
    text-align: center;
}
.services-title {
    font-size: 2.35rem;
    font-weight: 800;
    color: #23366b;
    margin-bottom: 28px;
    font-family: 'Montserrat', Arial, sans-serif;
    letter-spacing: 1.1px;
}
.service-cards {
    display: flex;
    gap: 32px;
    justify-content: center;
    flex-wrap: wrap;
}
.service-card {
    background: #fff;
    border: 1.5px solid #2223;
    border-radius: 18px;
    box-shadow: 0 8px 32px #cce8ff15;
    width: 300px;
    max-width: 95vw;
    padding: 30px 18px 22px 18px;
    transition: box-shadow 0.18s, transform 0.19s;
    text-align: center;
    margin-bottom: 12px;
}
.service-card:hover {
    box-shadow: 0 14px 40px #2b65f044;
    transform: translateY(-8px) scale(1.032);
}
.service-icon {
    font-size: 2.8rem;
    margin-bottom: 14px;
    color: #2463ff;
    display: block;
}
.service-card h3 {
    font-size: 1.33rem;
    font-weight: 700;
    margin-bottom: 13px;
    letter-spacing: 0.3px;
}
.service-card p {
    font-size: 1.06rem;
    color: #444;
    margin-bottom: 24px;
}
.service-card .card-btn {
    background: #fff;
    color: #23366b;
    border: 1.3px solid #c0c4c7;
    border-radius: 32px;
    padding: 12px 34px;
    font-weight: 700;
    box-shadow: 0 2px 12px #cce8ff26;
    transition: background 0.16s, color 0.16s;
    cursor: pointer;
}
.service-card .card-btn:hover {
    background: #2463ff;
    color: #fff;
    border-color: #2463ff;
}
@media (max-width: 850px) {
    .hero-title { font-size: 2rem; }
    .services-title { font-size: 1.5rem; }
    .service-cards { gap: 16px; }
    .service-card { width: 95vw; }
}
</style>

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
            <img src="../../images/anh2.jpg" alt="Pet Service">
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
