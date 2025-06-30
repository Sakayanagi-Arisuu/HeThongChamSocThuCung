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

<style>
.slide-container {
    width: 100%;
    max-width: 830px;
    margin: 0 auto;
    position: relative;
    min-height: 390px;
    background: none;
    overflow: hidden;
}
.slide-block {
    position: absolute;
    width: 100%;
    left: 0; top: 0;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.6s cubic-bezier(.47,1.64,.41,.8);
    z-index: 1;
    background: none;
    min-height: 380px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
}
.slide-block.active {
    opacity: 1;
    transform: translateX(0);
    z-index: 2;
}
.slide-block.slide-out-left {
    opacity: 0;
    transform: translateX(-100%);
    z-index: 1;
}
.slide-block.slide-out-right {
    opacity: 0;
    transform: translateX(100%);
    z-index: 1;
}
.slide-block h2 {
    font-size: 2rem;
    font-weight: bold;
    margin: 16px 0 10px 0;
}
.slide-block p {
    margin: 0 auto 20px auto;
    max-width: 620px;
    color: #444;
    text-align: center;
}
.slide-arrow {
    position: absolute;
    top: 46%;
    background: #fff;
    border: none;
    border-radius: 50%;
    width: 44px;
    height: 44px;
    font-size: 1.7rem;
    color: #3498db;
    box-shadow: 0 2px 12px #8ecaff55;
    cursor: pointer;
    z-index: 10;
    transition: background 0.22s;
}
.slide-left { left: -18px; }
.slide-right { right: -18px; }
.slide-arrow:hover {
    background: #d7f0fa;
    color: #176ebc;
}
.aboutus-list-img {
    display: flex;
    justify-content: center;
    gap: 36px;
    flex-wrap: wrap;
    margin-top: 18px;
}
.aboutus-list-img div {
    text-align: center;
}
.aboutus-list-img img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 2px 18px #d4e6ff44;
    border: 3.5px solid #fff;
    background: #f5f8ff;
    margin-bottom: 9px;
    transition: transform 0.32s;
}
.aboutus-list-img img:hover {
    transform: scale(1.1) rotate(-3deg);
    box-shadow: 0 8px 28px rgba(100, 150, 255, 0.17);
}
.team-members {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 34px;
    margin-top: 22px;
}
.team-member {
    background: #f7fafd;
    border-radius: 18px;
    padding: 20px 14px 14px 14px;
    width: 185px;
    box-shadow: 0 0 12px #e3edff50;
    text-align: center;
    transition: transform 0.24s, box-shadow 0.24s;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.team-member:hover {
    transform: translateY(-8px) scale(1.045);
    box-shadow: 0 8px 24px #6bc1ff30;
    background: #e8f3ff;
}
.team-member img {
    width: 78px;
    height: 78px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 9px;
    border: 2.5px solid #54a0ff38;
    background: #fff;
}
.doctor-info b {
    font-size: 1.09em;
    color: #222;
    display: block;
    margin-bottom: 4px;
}
.doctor-info span {
    color: #558;
    font-size: 0.98em;
}
@media (max-width: 700px) {
    .aboutus-list-img, .team-members { gap: 18px;}
    .team-member { width: 140px; padding: 10px 4px;}
    .aboutus-list-img img { width: 80px; height: 80px;}
}
</style>

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
                        <img src="/HeThongChamSocThuCung/images/cho.jpg" alt="Chó cưng">
                        <div>Chó cưng</div>
                    </div>
                    <div>
                        <img src="/HeThongChamSocThuCung/images/meo.jpg" alt="Mèo cưng">
                        <div>Mèo cưng</div>
                    </div>
                    <div>
                        <img src="/HeThongChamSocThuCung/images/hamster.jpg" alt="Hamster">
                        <div>Hamster</div>
                    </div>
                    <div>
                        <img src="/HeThongChamSocThuCung/images/vet.jpg" alt="Chim cảnh">
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
                        <img src="/HeThongChamSocThuCung/images/trang.jpg" alt="Bác sĩ Trang">
                        <div class="doctor-info">
                            <b>BS. Thuỳ Trang</b>
                            <span>Chuyên khoa nội & tiêm phòng</span>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="/HeThongChamSocThuCung/images/binh.jpg" alt="Bác sĩ Bình">
                        <div class="doctor-info">
                            <b>BS. Dương Ngọc Bình</b>
                            <span>Chuyên gia dinh dưỡng thú cưng</span>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="/HeThongChamSocThuCung/images/trinh.jpg" alt="Bác sĩ Trinh">
                        <div class="doctor-info">
                            <b>BS. Thuỳ Trinh</b>
                            <span>Phẫu thuật & spa thú cưng</span>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="/HeThongChamSocThuCung/images/thuy.jpg" alt="Bác sĩ Thuý">
                        <div class="doctor-info">
                            <b>BS. Thanh Thuý</b>
                            <span>Khám chuyên sâu & tư vấn chăm sóc</span>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="/HeThongChamSocThuCung/images/anh.jpg" alt="Bác sĩ Quý">
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

<script>
const slides = Array.from(document.querySelectorAll('.slide-block'));
const leftBtn = document.querySelector('.slide-left');
const rightBtn = document.querySelector('.slide-right');
let curr = 0;
function showSlide(newIndex, dir) {
    if (newIndex === curr) return;
    slides[curr].classList.remove('active');
    slides[curr].classList.add(dir === 'left' ? 'slide-out-left' : 'slide-out-right');
    slides[newIndex].classList.remove('slide-out-left', 'slide-out-right');
    slides[newIndex].classList.add('active');
    curr = newIndex;
}
function nextSlide(dir = 'right') {
    let newIndex = (curr + (dir === 'right' ? 1 : slides.length - 1)) % slides.length;
    showSlide(newIndex, dir);
}
leftBtn.onclick = () => { nextSlide('left'); resetAuto(); }
rightBtn.onclick = () => { nextSlide('right'); resetAuto(); }
function autoSlide() { nextSlide('right'); }
function resetAuto() {
    clearInterval(intervalID);
    intervalID = setInterval(autoSlide, 5200);
}
let intervalID = setInterval(autoSlide, 5200);
</script>

<?php include '../../includes/footer.php'; ?>
