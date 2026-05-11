-- MySQL dump 10.13  Distrib 8.0.45, for Linux (aarch64)
--
-- Host: localhost    Database: mystore
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'fas fa-box',
  `category_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Thời Trang Nam','fas fa-tshirt','https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=100&h=100&fit=crop'),(2,'Thời Trang Nữ','fas fa-female','https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?w=100&h=100&fit=crop'),(3,'Điện Thoại & Phụ Kiện','fas fa-mobile-alt','https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=100&h=100&fit=crop'),(4,'Máy Tính & Laptop','fas fa-laptop','https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=100&h=100&fit=crop'),(5,'Thiết Bị Điện Tử','fas fa-headphones','https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&h=100&fit=crop'),(6,'Sức Khỏe & Làm Đẹp','fas fa-heartbeat','https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=100&h=100&fit=crop'),(7,'Nhà Cửa & Đời Sống','fas fa-home','https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=100&h=100&fit=crop'),(8,'Giày Dép','fas fa-shoe-prints','https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=100&h=100&fit=crop'),(9,'Đồ Chơi','fas fa-gamepad','https://images.unsplash.com/photo-1558060370-d644479cb6f7?w=100&h=100&fit=crop'),(10,'Túi Ví','fas fa-shopping-bag','https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=100&h=100&fit=crop');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Mới',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (2,'PHAM VAN NHAT','0966917942','nhaterik@gmail.com','test','Đã liên hệ','2026-05-07 02:27:11');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `fk_order_item` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (2,8,11,2,1890000.00),(3,9,11,1,1890000.00),(4,10,23,2,200000.00),(5,11,8,1,32990000.00),(6,11,14,1,320000.00),(7,12,8,1,32990000.00),(8,13,24,1,150000000.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `buyer_id` int NOT NULL,
  `seller_id` int NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` float NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Chá» Xá»­ LÃ½',
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (8,6,1,'INV-1778148924-7989',3780000,'Chờ xác nhận','2026-05-07 10:15:24'),(9,6,1,'INV-1778149139-4733',1890000,'Chờ xác nhận','2026-05-07 10:18:59'),(10,5,5,'INV-1778153547-7721',400000,'Đang giao hàng','2026-05-07 11:32:27'),(11,3,1,'INV-1778214894-5086',33310000,'Chờ xác nhận','2026-05-08 04:34:54'),(12,5,1,'INV-1778221063-2892',32990000,'Chờ xác nhận','2026-05-08 06:17:43'),(13,5,3,'INV-1778221111-9025',150000000,'Chờ xác nhận','2026-05-08 06:18:31');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `image_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`image_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (20,1,'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&h=600&fit=crop',1),(21,1,'https://images.unsplash.com/photo-1622445275576-721325763afe?w=600&h=600&fit=crop',2),(22,1,'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&h=600&fit=crop',3),(23,3,'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&h=600&fit=crop',1),(24,3,'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=600&h=600&fit=crop',2),(25,3,'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&h=600&fit=crop',3),(26,4,'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&h=600&fit=crop',1),(27,4,'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=600&h=600&fit=crop',2),(28,4,'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=600&h=600&fit=crop',3),(29,4,'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?w=600&h=600&fit=crop',4),(30,5,'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?w=600&h=600&fit=crop',1),(31,5,'https://images.unsplash.com/photo-1588423771073-b8903faa2c2a?w=600&h=600&fit=crop',2),(32,8,'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&h=600&fit=crop',1),(33,8,'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=600&h=600&fit=crop',2),(34,8,'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=600&h=600&fit=crop',3),(35,11,'https://images.unsplash.com/photo-1594035910387-fea081ac23fc?w=600&h=600&fit=crop',1),(36,11,'https://images.unsplash.com/photo-1541643600914-78b084683601?w=600&h=600&fit=crop',2),(37,20,'https://images.unsplash.com/photo-1648455069346-6a625c488e7a?w=600&h=600&fit=crop',1),(38,20,'https://images.unsplash.com/photo-1585515320310-259814833e62?w=600&h=600&fit=crop',2),(39,23,'assets/images/product_5_1778118498_extra1.webp',1);
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `seller_id` int NOT NULL,
  `product_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` decimal(15,2) NOT NULL,
  `product_image1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rating` decimal(2,1) DEFAULT '5.0',
  `category_id` int DEFAULT NULL,
  `sold_count` int DEFAULT '0',
  `discount_percent` int DEFAULT '0',
  `product_stock` int DEFAULT '50',
  PRIMARY KEY (`product_id`),
  KEY `seller_id` (`seller_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Áo Thun Nam Cotton Premium','Áo thun nam chất liệu 100% cotton Mỹ cao cấp, thấm hút mồ hôi tốt. Form Regular Fit thoải mái. Size M-XXL.',189000.00,'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.8,1,37,0,50),(2,1,'Quần Jeans Slim Fit Xanh Đậm','Quần jeans nam form slim fit co giãn 4 chiều, chất denim Nhật Bản mềm mại. Wash đậm classic.',350000.00,'https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.5,1,379,25,50),(3,1,'Giày Sneaker Trắng Classic','Giày thể thao sneaker trắng unisex siêu nhẹ 280g, đế cao su chống trượt. Thiết kế tối giản.',450000.00,'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.9,8,274,0,50),(4,1,'iPhone 15 Pro Max 256GB','Chip A17 Pro, Camera 48MP, Khung Titanium. Màn hình Super Retina XDR 6.7 inch. Chính hãng VN/A.',28990000.00,'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400&h=400&fit=crop','2026-04-24 13:32:10',5.0,3,226,0,50),(5,1,'Tai Nghe AirPods Pro 2','Chống ồn ANC thế hệ 2, Spatial Audio, chip H2. Chống nước IPX4. Pin 6h liên tục.',5490000.00,'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.8,5,296,15,50),(6,1,'Bàn Phím Cơ Gaming RGB 75%','Switch Gateron Pro, keycap PBT doubleshot, LED RGB 16.8 triệu màu. USB-C / Bluetooth / 2.4GHz.',1290000.00,'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.7,5,292,10,50),(7,1,'Chuột Không Dây Ergonomic','Chuột công thái học, DPI 4000, pin sạc Type-C dùng 3 tháng. Bluetooth 5.0 & USB Receiver.',290000.00,'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.3,5,62,30,50),(8,1,'MacBook Air M3 15 inch','Chip M3 8-core CPU, 10-core GPU, RAM 16GB, SSD 512GB. Màn hình Liquid Retina 15.3 inch. 1.51kg.',32990000.00,'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=400&h=400&fit=crop','2026-04-24 13:32:10',5.0,4,425,0,48),(9,1,'Sạc Dự Phòng 20000mAh PD 65W','Sạc PD 65W cho laptop, QC 3.0 cho điện thoại. 2 cổng Type-C + 1 USB-A. Màn hình LED.',580000.00,'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.6,3,427,20,50),(10,1,'Ốp Lưng MagSafe iPhone 15','Ốp trong suốt chống vàng, viền TPU chống sốc, mặt PC cứng. MagSafe nam châm. Mỏng 1.2mm.',129000.00,'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.4,3,351,35,50),(11,1,'Nước Hoa Nam Bleu EDP 100ml','Hương gỗ trầm ấm pha aromatic tươi mát. Lưu hương 8-12 tiếng. Fullbox nguyên seal.',1890000.00,'https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.9,6,466,0,47),(12,1,'Sữa Rửa Mặt Than Tre Detox','Than hoạt tính Nhật Bản loại bỏ 99% bã nhờn. Dịu nhẹ cho da nhạy cảm. 150ml.',159000.00,'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.2,6,266,10,50),(13,1,'Kem Chống Nắng SPF50+ PA++++','Phổ rộng UVA/UVB, sữa mỏng nhẹ không bết, kiềm dầu 12h. Centella Asiatica. 50ml.',245000.00,'https://images.unsplash.com/photo-1556227834-09f1de7a7d14?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.5,6,423,0,50),(14,1,'Túi Xách Nữ Da PU Phong Cách','Túi đeo chéo nữ da PU cao cấp, khóa kim loại bền đẹp. Dây đeo chỉnh dài ngắn.',320000.00,'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=400&h=400&fit=crop','2026-04-24 13:32:10',3.9,10,308,15,49),(15,1,'Son Kem Lì Velvet Đỏ Mận','Màu đỏ mận trendy, kem lì mịn nhung, bám 8-10 tiếng. Dưỡng ẩm không khô môi. 3.5g.',199000.00,'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.6,6,263,20,50),(16,1,'Máy Sấy Tóc Ion Âm 2200W','Công suất 2200W, Ion âm bảo vệ tóc mềm mượt. 3 mức nhiệt + 2 tốc độ gió.',550000.00,'https://images.unsplash.com/photo-1585241645927-c7a8e5840c42?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.7,6,382,0,50),(17,1,'Váy Liền Hoa Nhí Vintage','Đầm liền dáng xoè chữ A, vải voan hoa nhí. Cổ vuông thanh lịch, tay bồng. Size S-XL.',259000.00,'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.4,2,109,25,50),(18,1,'Áo Khoác Gió Chống Nước Unisex','Áo 2 lớp chống nước chống gió, lớp trong lưới thoáng khí. Mũ trùm tháo rời.',310000.00,'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.5,1,390,0,50),(19,1,'Gấu Bông Capybara Khổng Lồ 60cm','Capybara siêu mềm mịn, bông PP 3D không xẹp. Vải nhung Hàn Quốc. Quà tặng cực hot.',199000.00,'https://images.unsplash.com/photo-1559715541-5daf8a0296d0?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.9,9,112,10,50),(20,1,'Nồi Chiên Không Dầu 5.5L Digital','Giảm 80% dầu mỡ, cảm ứng LED, 8 chế độ nấu. Lồng chống dính. 1700W.',1350000.00,'https://images.unsplash.com/photo-1585515320310-259814833e62?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.8,7,382,15,50),(21,1,'Bình Giữ Nhiệt Inox 1000ml','Inox 316 food-grade, giữ nóng 12h / lạnh 24h. Nắp chống gỉ, miệng rộng.',189000.00,'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.5,7,62,30,50),(22,1,'Đồng Hồ Thông Minh Fitness','Nhịp tim 24/7, SpO2, giấc ngủ. AMOLED 1.75 inch. Chống nước 5ATM. Pin 14 ngày.',890000.00,'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=400&h=400&fit=crop','2026-04-24 13:32:10',4.6,5,157,0,50),(23,5,'Chống nắng VIP pro sửa','Chống nắng dành cho mọi lứa tuổi.',200000.00,'assets/images/product_5_1778118568_u1.webp','2026-05-07 01:48:18',NULL,6,0,2,0),(24,3,'Iphone 18','Silent',150000000.00,'assets/images/product_3_1778211417_1.webp','2026-05-08 03:36:57',5.0,4,1,1,49);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default_user.png',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Người Bán 1','seller1@gmail.com','$2y$10$tZ2cIu.D9I3u5pPz9.36Z.2G1iU5A8M3eYI9U8NqVbX9K0n0H0B6C',NULL,NULL,'default_user.png',0),(2,'Khách Hàng','buyer@gmail.com','$2y$10$tZ2cIu.D9I3u5pPz9.36Z.2G1iU5A8M3eYI9U8NqVbX9K0n0H0B6C',NULL,NULL,'default_user.png',0),(3,'nhaterik','nhaterik@gmail.com','password123','','','user_3_1778213908.jpeg',1),(4,'normal_user','user@example.com','password123','Vietnam','0123456789','default_user.png',0),(5,'staff1','staff1@gmail.com','$2y$10$F8So0BaOQRt9.cfkkon5jOimjVCYScB2hav2anjKHvcCrLyQUBtZq',NULL,NULL,'default_user.png',0),(6,'testuser','testuser@example.com','$2y$10$aYFfwAcw6W1NWLlY7rLr0epQhl5ZlGxBKah3bAFeKh1cLe/OblvBW',NULL,NULL,'default_user.png',0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-11  8:09:48
