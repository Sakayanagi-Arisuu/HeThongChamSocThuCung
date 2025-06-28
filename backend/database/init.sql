CREATE DATABASE user_roles_db;
USE user_roles_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    contact_info VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
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
    phone VARCHAR(20) AFTER customer_id,
    doctor_id INT,
    service VARCHAR(100) AFTER schedule_time,
    schedule_time DATETIME,
    status ENUM('Đã đặt', 'Đã khám', 'Đã hủy', 'Check-in'),
    reason TEXT,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE,
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
    order_id INT,
    amount DECIMAL(10, 2),
    status ENUM('Đã thanh toán', 'Chưa thanh toán'),
    paid_at DATETIME,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
    FOREIGN KEY (order_id) REFERENCES orders(id)
);
-- Bảng sản phẩm
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL, -- số lượng tồn kho
    image VARCHAR(255), -- đường dẫn ảnh sản phẩm
    category VARCHAR(50), -- phân loại sản phẩm
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
-- Bảng đơn hàng
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    total_amount DECIMAL(10,2),
    status ENUM('Chờ thanh toán', 'Đã thanh toán', 'Đã hủy') DEFAULT 'Chờ thanh toán',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    paid_at DATETIME,
    FOREIGN KEY (customer_id) REFERENCES users(id)
);
