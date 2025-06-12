CREATE DATABASE user_roles_db;
USE user_roles_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'bác sĩ', 'nhân viên', 'người dùng') DEFAULT 'người dùng'
);

-- Thêm 3 tài khoản mẫu
INSERT INTO users (username, password, role) VALUES
('admin', SHA2('admin123', 256), 'admin'),
('bacsi', SHA2('bacsi123', 256), 'bác sĩ'),
('nhanvien', SHA2('nv123', 256), 'nhân viên');