SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_image1` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`product_id`),
  KEY `seller_id` (`seller_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm một user mẫu (password là 123456)
INSERT INTO `users` (`user_id`, `username`, `email`, `password`) VALUES
(1, 'Người Bán 1', 'seller1@gmail.com', '$2y$10$tZ2cIu.D9I3u5pPz9.36Z.2G1iU5A8M3eYI9U8NqVbX9K0n0H0B6C'),
(2, 'Khách Hàng', 'buyer@gmail.com', '$2y$10$tZ2cIu.D9I3u5pPz9.36Z.2G1iU5A8M3eYI9U8NqVbX9K0n0H0B6C');

-- Thêm một số sản phẩm mẫu
INSERT INTO `products` (`seller_id`, `product_title`, `description`, `product_price`, `product_image1`) VALUES
(1, 'Điện Thoại Mới Nhất 2026', 'Sản phẩm siêu hot.', '15000000.00', 'phone_sample.jpg'),
(1, 'Tai Nghe Bluetooth Xịn xò', 'Nghe nhạc cực đỉnh.', '500000.00', 'headphone_sample.jpg');

COMMIT;
