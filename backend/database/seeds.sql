USE user_roles_db;

-- Thêm 20 sản phẩm
INSERT INTO products (name, description, price, stock, image, category) VALUES
('Thức ăn hạt cao cấp cho chó', 'Thức ăn đầy đủ dưỡng chất cho chó trưởng thành.', 150000, 50, 'dog_food_1.jpg', 'Thức ăn cho chó'),
('Xương gặm sạch răng', 'Xương nhai giúp chó sạch răng, thơm miệng.', 45000, 80, 'dog_bone.jpg', 'Thức ăn cho chó'),
('Thức ăn ướt vị cá ngừ cho mèo', 'Đồ ăn ngon miệng, bổ dưỡng cho mèo.', 55000, 60, 'cat_food_tuna.jpg', 'Thức ăn cho mèo'),
('Pate gà cho mèo con', 'Thức ăn mềm dễ tiêu hoá.', 60000, 40, 'cat_pate.jpg', 'Thức ăn cho mèo'),
('Sữa bột cho chó con', 'Dinh dưỡng thay thế sữa mẹ.', 120000, 25, 'puppy_milk.jpg', 'Sản phẩm mới'),
('Vòng cổ phát sáng ban đêm', 'Giúp nhận diện thú cưng vào buổi tối.', 75000, 30, 'led_collar.jpg', 'Sản phẩm mới'),
('Ống tiêm tiệt trùng 5ml', 'Dùng trong điều trị thú cưng.', 8000, 200, 'syringe.jpg', 'Vật tư y tế'),
('Băng gạc thú y', 'Băng gạc chuyên dụng cho thú bị thương.', 20000, 100, 'bandage.jpg', 'Vật tư y tế'),
('Nhiệt kế điện tử thú cưng', 'Đo nhiệt độ nhanh, chính xác.', 90000, 15, 'thermometer.jpg', 'Vật tư y tế'),
('Lược chải lông chó mèo', 'Giúp loại bỏ lông rụng hiệu quả.', 45000, 50, 'brush.jpg', 'Vật dụng cho chó mèo'),
('Bình uống nước tự động', 'Tiện lợi khi vắng nhà.', 100000, 20, 'auto_water.jpg', 'Vật dụng cho chó mèo'),
('Thảm vệ sinh cho chó', 'Dễ dàng vệ sinh, khử mùi.', 65000, 60, 'pee_pad.jpg', 'Vật dụng cho chó mèo'),
('Đồ chơi hình cá cho mèo', 'Mèo chơi và mài móng.', 35000, 45, 'fish_toy.jpg', 'Vật dụng cho chó mèo'),
('Dây dắt chó co giãn', 'Thoải mái khi đi dạo.', 85000, 25, 'leash.jpg', 'Vật dụng cho chó mèo'),
('Balo vận chuyển thú cưng', 'Có lỗ thông khí, dễ quan sát.', 200000, 10, 'pet_backpack.jpg', 'Vật dụng cho chó mèo'),
('Xịt khử mùi chuồng trại', 'Khử mùi hôi hiệu quả.', 70000, 40, 'spray.jpg', 'Vật dụng cho chó mèo'),
('Pate vị cá hồi cho mèo lớn', 'Thơm ngon, dễ tiêu hoá.', 58000, 50, 'salmon_pate.jpg', 'Thức ăn cho mèo'),
('Thức ăn khô cho chó nhỏ', 'Hạt nhỏ dễ nhai, giàu protein.', 130000, 35, 'mini_dog_food.jpg', 'Thức ăn cho chó'),
('Găng tay thú y', 'Dùng 1 lần, đảm bảo vệ sinh.', 10000, 150, 'gloves.jpg', 'Vật tư y tế'),
('Lều nghỉ gấp gọn cho chó mèo', 'Gọn nhẹ, dễ mang theo.', 160000, 12, 'pet_tent.jpg', 'Sản phẩm mới');
