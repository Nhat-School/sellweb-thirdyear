SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Clear corrupted data
DELETE FROM product_images;
DELETE FROM cart;
DELETE FROM products;
DELETE FROM categories;

ALTER TABLE products AUTO_INCREMENT = 1;
ALTER TABLE categories AUTO_INCREMENT = 1;

-- Re-insert categories
INSERT INTO categories (category_id, category_title, category_icon, category_image) VALUES
(1, 'Thời Trang Nam', 'fas fa-tshirt', 'https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=100&h=100&fit=crop'),
(2, 'Thời Trang Nữ', 'fas fa-female', 'https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=100&h=100&fit=crop'),
(3, 'Điện Thoại & Phụ Kiện', 'fas fa-mobile-alt', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=100&h=100&fit=crop'),
(4, 'Máy Tính & Laptop', 'fas fa-laptop', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=100&h=100&fit=crop'),
(5, 'Thiết Bị Điện Tử', 'fas fa-headphones', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&h=100&fit=crop'),
(6, 'Sức Khỏe & Làm Đẹp', 'fas fa-heartbeat', 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=100&h=100&fit=crop'),
(7, 'Nhà Cửa & Đời Sống', 'fas fa-home', 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=100&h=100&fit=crop'),
(8, 'Giày Dép', 'fas fa-shoe-prints', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=100&h=100&fit=crop'),
(9, 'Đồ Chơi', 'fas fa-gamepad', 'https://images.unsplash.com/photo-1558060370-d644479cb6f7?w=100&h=100&fit=crop'),
(10, 'Túi Ví', 'fas fa-shopping-bag', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=100&h=100&fit=crop');

-- Re-insert products
INSERT INTO products (seller_id, product_title, description, product_price, product_image1, rating, category_id) VALUES
(1, 'Áo Thun Nam Cotton Premium', 'Áo thun nam chất liệu 100% cotton Mỹ cao cấp, thấm hút mồ hôi tốt. Form Regular Fit thoải mái. Size M-XXL.', 189000, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop', 4.8, 1),
(1, 'Quần Jeans Slim Fit Xanh Đậm', 'Quần jeans nam form slim fit co giãn 4 chiều, chất denim Nhật Bản mềm mại. Wash đậm classic.', 350000, 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=400&fit=crop', 4.5, 1),
(1, 'Giày Sneaker Trắng Classic', 'Giày thể thao sneaker trắng unisex siêu nhẹ 280g, đế cao su chống trượt. Thiết kế tối giản.', 450000, 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=400&fit=crop', 4.9, 8),
(1, 'iPhone 15 Pro Max 256GB', 'Chip A17 Pro, Camera 48MP, Khung Titanium. Màn hình Super Retina XDR 6.7 inch. Chính hãng VN/A.', 28990000, 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=400&h=400&fit=crop', 5.0, 3),
(1, 'Tai Nghe AirPods Pro 2', 'Chống ồn ANC thế hệ 2, Spatial Audio, chip H2. Chống nước IPX4. Pin 6h liên tục.', 5490000, 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?w=400&h=400&fit=crop', 4.8, 5),
(1, 'Bàn Phím Cơ Gaming RGB 75%', 'Switch Gateron Pro, keycap PBT doubleshot, LED RGB 16.8 triệu màu. USB-C / Bluetooth / 2.4GHz.', 1290000, 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=400&h=400&fit=crop', 4.7, 5),
(1, 'Chuột Không Dây Ergonomic', 'Chuột công thái học, DPI 4000, pin sạc Type-C dùng 3 tháng. Bluetooth 5.0 & USB Receiver.', 290000, 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=400&h=400&fit=crop', 4.3, 5),
(1, 'MacBook Air M3 15 inch', 'Chip M3 8-core CPU, 10-core GPU, RAM 16GB, SSD 512GB. Màn hình Liquid Retina 15.3 inch. 1.51kg.', 32990000, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400&h=400&fit=crop', 5.0, 4),
(1, 'Sạc Dự Phòng 20000mAh PD 65W', 'Sạc PD 65W cho laptop, QC 3.0 cho điện thoại. 2 cổng Type-C + 1 USB-A. Màn hình LED.', 580000, 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=400&h=400&fit=crop', 4.6, 3),
(1, 'Ốp Lưng MagSafe iPhone 15', 'Ốp trong suốt chống vàng, viền TPU chống sốc, mặt PC cứng. MagSafe nam châm. Mỏng 1.2mm.', 129000, 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?w=400&h=400&fit=crop', 4.4, 3),
(1, 'Nước Hoa Nam Bleu EDP 100ml', 'Hương gỗ trầm ấm pha aromatic tươi mát. Lưu hương 8-12 tiếng. Fullbox nguyên seal.', 1890000, 'https://images.unsplash.com/photo-1594035910387-fea081ac23fc?w=400&h=400&fit=crop', 4.9, 6),
(1, 'Sữa Rửa Mặt Than Tre Detox', 'Than hoạt tính Nhật Bản loại bỏ 99% bã nhờn. Dịu nhẹ cho da nhạy cảm. 150ml.', 159000, 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=400&fit=crop', 4.2, 6),
(1, 'Kem Chống Nắng SPF50+ PA++++', 'Phổ rộng UVA/UVB, sữa mỏng nhẹ không bết, kiềm dầu 12h. Centella Asiatica. 50ml.', 245000, 'https://images.unsplash.com/photo-1625093525665-2be24c78d5c0?w=400&h=400&fit=crop', 4.5, 6),
(1, 'Túi Xách Nữ Da PU Phong Cách', 'Túi đeo chéo nữ da PU cao cấp, khóa kim loại bền đẹp. Dây đeo chỉnh dài ngắn.', 320000, 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400&h=400&fit=crop', 3.9, 10),
(1, 'Son Kem Lì Velvet Đỏ Mận', 'Màu đỏ mận trendy, kem lì mịn nhung, bám 8-10 tiếng. Dưỡng ẩm không khô môi. 3.5g.', 199000, 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=400&fit=crop', 4.6, 6),
(1, 'Máy Sấy Tóc Ion Âm 2200W', 'Công suất 2200W, Ion âm bảo vệ tóc mềm mượt. 3 mức nhiệt + 2 tốc độ gió.', 550000, 'https://images.unsplash.com/photo-1522338140-7f49f062ebf3?w=400&h=400&fit=crop', 4.7, 6),
(1, 'Váy Liền Hoa Nhí Vintage', 'Đầm liền dáng xoè chữ A, vải voan hoa nhí. Cổ vuông thanh lịch, tay bồng. Size S-XL.', 259000, 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=400&h=400&fit=crop', 4.4, 2),
(1, 'Áo Khoác Gió Chống Nước Unisex', 'Áo 2 lớp chống nước chống gió, lớp trong lưới thoáng khí. Mũ trùm tháo rời.', 310000, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&h=400&fit=crop', 4.5, 1),
(1, 'Gấu Bông Capybara Khổng Lồ 60cm', 'Capybara siêu mềm mịn, bông PP 3D không xẹp. Vải nhung Hàn Quốc. Quà tặng cực hot.', 199000, 'https://images.unsplash.com/photo-1559715541-5daf8a0296d0?w=400&h=400&fit=crop', 4.9, 9),
(1, 'Nồi Chiên Không Dầu 5.5L Digital', 'Giảm 80% dầu mỡ, cảm ứng LED, 8 chế độ nấu. Lồng chống dính. 1700W.', 1350000, 'https://images.unsplash.com/photo-1648455069346-6a625c488e7a?w=400&h=400&fit=crop', 4.8, 7),
(1, 'Bình Giữ Nhiệt Inox 1000ml', 'Inox 316 food-grade, giữ nóng 12h / lạnh 24h. Nắp chống gỉ, miệng rộng.', 189000, 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=400&h=400&fit=crop', 4.5, 7),
(1, 'Đồng Hồ Thông Minh Fitness', 'Nhịp tim 24/7, SpO2, giấc ngủ. AMOLED 1.75 inch. Chống nước 5ATM. Pin 14 ngày.', 890000, 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=400&h=400&fit=crop', 4.6, 5);

-- Thêm ảnh phụ
INSERT INTO product_images (product_id, image_url, sort_order) VALUES
(1, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&h=600&fit=crop', 1),
(1, 'https://images.unsplash.com/photo-1622445275576-721325763afe?w=600&h=600&fit=crop', 2),
(1, 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&h=600&fit=crop', 3),
(3, 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&h=600&fit=crop', 1),
(3, 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=600&h=600&fit=crop', 2),
(3, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&h=600&fit=crop', 3),
(4, 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&h=600&fit=crop', 1),
(4, 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=600&h=600&fit=crop', 2),
(4, 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=600&h=600&fit=crop', 3),
(4, 'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?w=600&h=600&fit=crop', 4),
(5, 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?w=600&h=600&fit=crop', 1),
(5, 'https://images.unsplash.com/photo-1588423771073-b8903faa2c2a?w=600&h=600&fit=crop', 2),
(8, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&h=600&fit=crop', 1),
(8, 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=600&h=600&fit=crop', 2),
(8, 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=600&h=600&fit=crop', 3),
(11, 'https://images.unsplash.com/photo-1594035910387-fea081ac23fc?w=600&h=600&fit=crop', 1),
(11, 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=600&h=600&fit=crop', 2),
(20, 'https://images.unsplash.com/photo-1648455069346-6a625c488e7a?w=600&h=600&fit=crop', 1),
(20, 'https://images.unsplash.com/photo-1585515320310-259814833e62?w=600&h=600&fit=crop', 2);

-- Also fix users
UPDATE users SET username = 'Người Bán 1' WHERE user_id = 1;
UPDATE users SET username = 'Khách Hàng' WHERE user_id = 2;
