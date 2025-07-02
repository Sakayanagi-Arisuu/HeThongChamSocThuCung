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
        include '../../includes/navbar_guest.php'; // Navbar cho khách chưa đăng nhập
}

?>
<!-- css -->
<link rel="stylesheet" href="/HeThongChamSocThuCung/assets/css/dashboards/about_us.css">

<div class="main-content">
    <div class="left-content" style="min-height: 440px; position: relative;">
        <div class="slide-container">
            <!-- Slide 1: Thú cưng -->
            <div class="slide-block active" id="slide-about">
                <h2>Về chúng tôi</h2>
                <p>Pet Care Services tự hào là đơn vị chăm sóc thú cưng với đội ngũ chuyên gia giàu kinh nghiệm.<br>
                Chúng tôi mang đến các dịch vụ toàn diện, tận tâm và chất lượng hàng đầu dành cho thú cưng của bạn.</p>
                <div class="aboutus-list-img">
                    <div>
                        <img src="/HeThongChamSocThuCung/assets/images/cho.jpg" alt="Chó cưng">
                        <div>Chó cưng</div>
                    </div>
                    <div>
                        <img src="/HeThongChamSocThuCung/assets/images/meo.jpg" alt="Mèo cưng">
                        <div>Mèo cưng</div>
                    </div>
                    <div>
                        <img src="/HeThongChamSocThuCung/assets/images/hamster.jpg" alt="Hamster">
                        <div>Hamster</div>
                    </div>
                    <div>
                        <img src="/HeThongChamSocThuCung/assets/images/vet.jpg" alt="Chim cảnh">
                        <div>Chim cảnh</div>
                    </div>
                </div>
            </div>
            <!-- Slide 2: Bác sĩ -->
            <div class="slide-block" id="slide-team">
                <h2>Đội ngũ bác sĩ</h2>
                <p>Đội ngũ bác sĩ giàu kinh nghiệm, tận tâm của Pet Care Services luôn sẵn sàng hỗ trợ và chăm sóc thú cưng của bạn.</p>
                <div class="team-members">
                    <div class="team-member">
                        <img src="/HeThongChamSocThuCung/assets/images/trang.jpg" alt="Bác sĩ Trang">
                        <div class="doctor-info">
                            <b>BS. Thuỳ Trang</b>
                            <span>Chuyên khoa nội & tiêm phòng</span>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="/HeThongChamSocThuCung/assets/images/binh.jpg" alt="Bác sĩ Bình">
                        <div class="doctor-info">
                            <b>BS. Dương Ngọc Bình</b>
                            <span>Chuyên gia dinh dưỡng thú cưng</span>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="/HeThongChamSocThuCung/assets/images/trinh.jpg" alt="Bác sĩ Trinh">
                        <div class="doctor-info">
                            <b>BS. Thuỳ Trinh</b>
                            <span>Phẫu thuật & spa thú cưng</span>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="/HeThongChamSocThuCung/assets/images/thuy.jpg" alt="Bác sĩ Thuý">
                        <div class="doctor-info">
                            <b>BS. Thanh Thuý</b>
                            <span>Khám chuyên sâu & tư vấn chăm sóc</span>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="/HeThongChamSocThuCung/assets/images/anh.jpg" alt="Bác sĩ Quý">
                        <div class="doctor-info">
                            <b>BS. Nguyễn Hoàng Thanh Quý</b>
                            <span>Khám ngoại – tiểu phẫu</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Nút mũi tên chuyển -->
            <button class="slide-arrow slide-left" title="Xem trước">&#8592;</button>
            <button class="slide-arrow slide-right" title="Xem tiếp">&#8594;</button>
        </div>
    </div>
    <?php include '../../includes/category.php'; ?>
</div>
<!-- script -->
<script src="/HeThongChamSocThuCung/assets/js/dashboards/about_us.js"></script>

<?php include '../../includes/footer.php'; ?>
