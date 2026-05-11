-- 1. Thêm cột product_stock vào bảng products
ALTER TABLE `products` ADD COLUMN `product_stock` INT DEFAULT 100;

-- 2. Tạo bảng orders (Đơn hàng)
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `order_id` INT NOT NULL AUTO_INCREMENT,
  `buyer_id` INT NOT NULL,
  `seller_id` INT NOT NULL,
  `total_amount` DECIMAL(15,2) NOT NULL,
  `status` VARCHAR(50) DEFAULT 'Chờ xác nhận',
  `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tạo bảng order_items (Chi tiết đơn hàng)
CREATE TABLE IF NOT EXISTS `order_items` (
  `item_id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(15,2) NOT NULL,
  PRIMARY KEY (`item_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Loại bỏ các đánh giá cũ nếu có và chuẩn bị cho việc xóa cột rating sau này (nếu cần)
-- Ở đây ta chỉ cần dừng việc hiển thị rating là được.
