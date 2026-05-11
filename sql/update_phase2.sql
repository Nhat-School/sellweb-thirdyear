-- Thêm các cột vào users
ALTER TABLE `users`
ADD COLUMN `address` varchar(255) DEFAULT NULL,
ADD COLUMN `contact` varchar(20) DEFAULT NULL,
ADD COLUMN `user_image` varchar(255) DEFAULT 'default_user.png';

-- Thêm rating vào products
ALTER TABLE `products`
ADD COLUMN `rating` DECIMAL(2,1) DEFAULT 5.0;

-- Tạo bảng giỏ hàng
CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`cart_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert 20 demo products using relative placeholder images which will fallback because they don't exist
INSERT INTO `products` (`seller_id`, `product_title`, `description`, `product_price`, `product_image1`, `rating`) VALUES
(1, 'Áo Thun Nam Cotton', 'Áo thun nam chất liệu 100% cotton thoáng mát, thấm hút mồ hôi tốt. Đường may tỉ mỉ, form dáng suông thoải mái. Có các size M, L, XL, XXL. Phù hợp dạo phố, mặc ở nhà.', 150000, 'shirt_demo.png', 4.5),
(1, 'Quần Jeans Nam Ôm Phong Cách', 'Quần jeans form ôm dáng, chất denim co giãn cực tốt, độ bền cao. Phù hợp phối đồ đi chơi, dạo phố.', 250000, 'jeans_demo.png', 4.0),
(1, 'Giày Thể Thao Mới Nhất VN', 'Giày siêu nhẹ, êm ái, bảo vệ chân tuyệt đối.', 350000, 'shoe_demo.png', 5.0),
(1, 'Tai Nghe Bluetooth Pro', 'Tai nghe âm thanh sống động, màng loa kép, chống ồn chủ động ANC.', 790000, 'headphone_demo.png', 4.8),
(1, 'Bàn Phím Cơ Gaming RGB', 'Switch Red siêu mượt, đèn LED 16 triệu màu tuỳ chỉnh.', 850000, 'keyboard_demo.png', 4.7),
(1, 'Chuột Không Dây Silent', 'Chuột văn phòng không gây tiếng ồn, thiết kế công thái học. Pin trâu xài 3 tháng.', 120000, 'mouse_demo.png', 4.2),
(1, 'Sạc Dự Phòng 20000mAh', 'Sạc siêu nhanh, dung lượng khủng đáp ứng mọi hành trình.', 380000, 'powerbank_demo.png', 4.6),
(1, 'Ốp Lưng Magsafe iPhone 15', 'Ốp lưng trong suốt chống bám vân tay, hỗ trợ sạc hít nam châm mạnh.', 90000, 'case_demo.png', 5.0),
(1, 'Nước Hoa Cao Cấp Nam', 'Hương gỗ trầm ấm, lưu hương lên đến 12h. Phù hợp cho những buổi tiệc đêm sang trọng.', 1200000, 'perfume_demo.png', 4.9),
(1, 'Sữa Rửa Mặt Tinh Chất Than Tre', 'Làm sạch sâu, loại bỏ bã nhờn, thích hợp cho da nhạy cảm.', 110000, 'facewash_demo.png', 4.1),
(1, 'Kem Chống Nắng Phổ Rộng', 'Bảo vệ mạnh mẽ dưới ánh nắng gay gắt, không gây bết dính.', 220000, 'sunscreen_demo.png', 4.4),
(1, 'Túi Xách Nữ Thời Trang', 'Túi hoạ tiết đan chéo cực độc, ngăn rộng rãi, dây đeo chỉnh tiện lợi.', 320000, 'bag_demo.png', 3.8),
(1, 'Son Kem Lì Chính Hãng', 'Màu son đỏ mận siêu trend, mềm môi độ giữ màu 8 tiếng.', 180000, 'lipstick_demo.png', 4.5),
(1, 'Máy Sấy Tóc Ion Âm', 'Sấy siêu tốc, bảo vệ tóc khỏe mạnh không làm xơ cứng.', 450000, 'dryer_demo.png', 4.7),
(1, 'Chân Váy Chữ A Xếp Ly', 'Thiết kế trẻ trung năng động hack dáng, dễ mix đồ.', 160000, 'skirt_demo.png', 4.3),
(1, 'Áo Khoác Gió Chống Nước', 'Áo khoác 2 lớp gọn nhẹ, cản gió và chống nước đi mưa phùn tốt.', 260000, 'jacket_demo.png', 4.6),
(1, 'Gấu Bông Capybara', 'Gấu bông cực hot hit siêu mềm mịn, nhồi bông PP xịn.', 99000, 'capybara_demo.png', 4.9),
(1, 'Set Nồi Chảo Từ Bếp Hồng Ngoại', 'Chống dính vân đá 5 lớp siêu bền.', 890000, 'pan_demo.png', 4.1),
(1, 'Nồi Chiên Không Dầu 5L', 'Chiên rán không ngập dầu tốt cho sức khoẻ, bảng điều khiển cảm ứng thông minh.', 1250000, 'airfryer_demo.png', 4.8),
(1, 'Bình Giữ Nhiệt 1000ml', 'Giữ đá 24h, giữ nóng 12h, nắp chống gỉ.', 150000, 'bottle_demo.png', 4.5);
