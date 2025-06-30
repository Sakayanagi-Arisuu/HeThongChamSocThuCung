CREATE DATABASE IF NOT EXISTS user_roles_db;
USE user_roles_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    contact_info VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    role ENUM('admin', 'doctor', 'customer', 'guest') DEFAULT 'guest'
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    total_amount DECIMAL(10,2),
    status ENUM('Chờ thanh toán', 'Đã thanh toán', 'Đã hủy') DEFAULT 'Chờ thanh toán',
    payment_status ENUM('Đã thanh toán', 'Chưa thanh toán') DEFAULT 'Chưa thanh toán',
    paid_at DATETIME,
    payment_method VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id)
);


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

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_id INT,
    customer_id INT,
    phone VARCHAR(20),
    doctor_id INT,
    schedule_time DATETIME,
    service VARCHAR(100),
    fee DECIMAL(10,2),
    payment_status ENUM('Đã thanh toán', 'Chưa thanh toán') DEFAULT 'Chưa thanh toán',
    paid_at DATETIME,
    status ENUM('Đã đặt', 'Đã khám', 'Đã hủy', 'Check-in'),
    reason TEXT,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (doctor_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS medical_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT,
    diagnosis TEXT,
    treatment TEXT,
    notes TEXT,
    medical_records ADD COLUMN fee FLOAT DEFAULT 0;
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);


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


CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    image VARCHAR(255),
    category VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
