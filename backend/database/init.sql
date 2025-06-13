CREATE DATABASE user_roles_db;
USE user_roles_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'bác sĩ', 'nhân viên', 'người dùng') DEFAULT 'người dùng'
);
-- Bảng thú cưng
CREATE TABLE IF NOT EXISTS pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT,
    name VARCHAR(100) NOT NULL,
    species VARCHAR(50),
    breed VARCHAR(50),
    birth_date DATE,
    gender ENUM('Đực', 'Cái'),
    weight FLOAT,
    notes TEXT,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng lịch hẹn khám
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_id INT,
    customer_id INT,
    doctor_id INT,
    schedule_time DATETIME,
    status ENUM('Đã đặt', 'Đã khám', 'Đã hủy', 'Check-in'),
    reason TEXT,
    FOREIGN KEY (pet_id) REFERENCES pets(id),
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);

-- Bảng hồ sơ khám bệnh
CREATE TABLE IF NOT EXISTS medical_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT,
    diagnosis TEXT,
    treatment TEXT,
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Bảng phòng/chuồng
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(50),
    type ENUM('Khám bệnh', 'Lưu trú'),
    status ENUM('Trống', 'Đang dùng'),
    notes TEXT
);

-- Bảng đánh giá từ khách hàng
CREATE TABLE IF NOT EXISTS feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    doctor_id INT,
    appointment_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (doctor_id) REFERENCES users(id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Bảng thống kê thanh toán (tuỳ chọn)
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT,
    amount DECIMAL(10, 2),
    status ENUM('Đã thanh toán', 'Chưa thanh toán'),
    paid_at DATETIME,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);