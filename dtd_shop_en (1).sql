-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th10 27, 2026 lúc 08:08 AM
-- Phiên bản máy phục vụ: 8.3.0
-- Phiên bản PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dtd_shop_en`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_name` varchar(150) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `description` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `slug`, `is_visible`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Apple', 'apple', 1, NULL, '2026-11-21 07:03:14', NULL),
(2, 'Samsung', 'samsung', 1, NULL, '2026-11-21 07:03:14', NULL),
(3, 'Xiaomi', 'xiaomi', 1, NULL, '2026-11-21 07:03:14', NULL),
(4, 'OPPO', 'oppo', 1, NULL, '2026-11-21 07:03:14', NULL),
(5, 'Google', 'google', 1, NULL, '2026-11-21 07:03:14', NULL),
(6, 'Google Pixel', 'google-pixel', 1, NULL, '2026-11-27 14:43:06', '2026-11-27 14:55:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `recipient_name` varchar(150) NOT NULL,
  `recipient_phone` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(40) NOT NULL DEFAULT 'cho_xac_nhan',
  `ordered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` text,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_code` (`order_code`),
  KEY `fk_orders_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_assignments`
--

DROP TABLE IF EXISTS `order_assignments`;
CREATE TABLE IF NOT EXISTS `order_assignments` (
  `assignment_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `employee_id` int UNSIGNED NOT NULL,
  `assigned_by_id` int UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ended_at` datetime DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'dang_xu_ly',
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`assignment_id`),
  UNIQUE KEY `uq_active_order_assignment` (((case when (`status` = _utf8mb4'dang_xu_ly') then `order_id` else NULL end))),
  KEY `fk_order_assignments_orders` (`order_id`),
  KEY `fk_assignments_employee` (`employee_id`),
  KEY `fk_assignments_assigned_by` (`assigned_by_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `order_detail_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `variant_id` int UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `configuration` varchar(255) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `category_name` varchar(150) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `line_total` decimal(25,2) GENERATED ALWAYS AS ((`unit_price` * `quantity`)) STORED,
  PRIMARY KEY (`order_detail_id`),
  KEY `fk_order_details_orders` (`order_id`),
  KEY `fk_order_details_variants` (`variant_id`)
) ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
CREATE TABLE IF NOT EXISTS `order_status_history` (
  `history_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `updated_by_id` int UNSIGNED NOT NULL,
  `old_status` varchar(40) DEFAULT NULL,
  `new_status` varchar(40) NOT NULL,
  `note` text,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`history_id`),
  KEY `fk_order_status_history_orders` (`order_id`),
  KEY `fk_order_history_updated_by` (`updated_by_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `method` varchar(30) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'cho_thanh_toan',
  `qr_content` text,
  `transaction_code` varchar(150) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `is_simulated` tinyint(1) NOT NULL DEFAULT '0',
  `note` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `transaction_code` (`transaction_code`),
  KEY `fk_payments_orders` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `brand` varchar(120) NOT NULL,
  `description` text,
  `image_list` json DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'dang_ban',
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `list_price` decimal(15,2) DEFAULT NULL,
  `old_price` decimal(15,2) DEFAULT NULL,
  `screen` varchar(50) DEFAULT NULL,
  `chip` varchar(100) DEFAULT NULL,
  `promotion_label` varchar(80) DEFAULT NULL,
  `slug` varchar(220) DEFAULT NULL,
  `technical_specs` text,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `uq_products_slug` (`slug`),
  KEY `fk_products_categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `brand`, `description`, `image_list`, `status`, `added_at`, `list_price`, `old_price`, `screen`, `chip`, `promotion_label`, `slug`, `technical_specs`, `is_featured`, `updated_at`) VALUES
(1, 1, 'iPhone 16 Pro Max', 'Apple', 'Sản phẩm iPhone 16 Pro Max chính hãng, màn hình 6,9″, vi xử lý A18 Pro. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-1.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 29490000.00, 34990000.00, '6,9″', 'A18 Pro', 'Bán chạy', 'iphone-16-pro-max-1', NULL, 0, NULL),
(2, 2, 'Samsung Galaxy S25 Ultra', 'Samsung', 'Sản phẩm Samsung Galaxy S25 Ultra chính hãng, màn hình 6,9″, vi xử lý Snapdragon 8 Elite. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-2.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 27990000.00, 33990000.00, '6,9″', 'Snapdragon 8 Elite', 'Giảm 17%', 'samsung-galaxy-s25-ultra-2', NULL, 0, NULL),
(3, 1, 'iPhone 16', 'Apple', 'Sản phẩm iPhone 16 chính hãng, màn hình 6,1″, vi xử lý A18. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-3.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 19490000.00, 22990000.00, '6,1″', 'A18', '', 'iphone-16-3', NULL, 0, NULL),
(4, 2, 'Samsung Galaxy A56 5G', 'Samsung', 'Sản phẩm Samsung Galaxy A56 5G chính hãng, màn hình 6,7″, vi xử lý Exynos 1580. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-4.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 8990000.00, 9990000.00, '6,7″', 'Exynos 1580', 'Trả góp 0%', 'samsung-galaxy-a56-5g-4', NULL, 0, NULL),
(5, 3, 'Xiaomi 15', 'Xiaomi', 'Sản phẩm Xiaomi 15 chính hãng, màn hình 6,36″, vi xử lý Snapdragon 8 Elite. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-5.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 19990000.00, 22990000.00, '6,36″', 'Snapdragon 8 Elite', '', 'xiaomi-15-5', NULL, 0, NULL),
(6, 4, 'OPPO Reno13 5G', 'OPPO', 'Sản phẩm OPPO Reno13 5G chính hãng, màn hình 6,59″, vi xử lý Dimensity 8350. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-6.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 13490000.00, 15990000.00, '6,59″', 'Dimensity 8350', '', 'oppo-reno13-5g-6', NULL, 0, NULL),
(7, 5, 'Google Pixel 9', 'Google', 'Sản phẩm Google Pixel 9 chính hãng, màn hình 6,3″, vi xử lý Google Tensor G4. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-7.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 15990000.00, 18990000.00, '6,3″', 'Google Tensor G4', '', 'google-pixel-9-7', NULL, 0, NULL),
(8, 3, 'Xiaomi Redmi Note 14', 'Xiaomi', 'Sản phẩm Xiaomi Redmi Note 14 chính hãng, màn hình 6,67″, vi xử lý Helio G99-Ultra. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-8.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 4690000.00, 5290000.00, '6,67″', 'Helio G99-Ultra', '', 'xiaomi-redmi-note-14-8', NULL, 0, NULL),
(11, 1, 'iphone duo', 'Apple', '123', '[\"uploads/7b144db29a7482f7dcff9668620f6dbe.png\"]', 'dang_ban', '2026-11-18 13:32:28', 50000000.00, 60000000.00, '7.6', 'a18', 'bán chạy', 'iphone-duo-11', NULL, 0, '2026-11-25 16:35:05'),
(15, 5, 'iPhone 17 Pro Max', 'Apple', '1235', '[\"assets/uploads/products/c553070120c6bca3813dbfdb33ca662e.png\", \"assets/uploads/products/72552c0813842d080837a6f3383d2d3d.png\"]', 'dang_ban', '2026-11-23 18:27:34', 34990000.00, 37990000.00, '', 'A19 Pro', 'Hàng mới', NULL, NULL, 0, '2026-11-25 14:40:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE IF NOT EXISTS `product_variants` (
  `variant_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `sku` varchar(100) NOT NULL,
  `ram` int UNSIGNED NOT NULL,
  `storage` int UNSIGNED NOT NULL,
  `color` varchar(80) NOT NULL,
  `sale_price` decimal(15,2) NOT NULL,
  `stock_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `is_on_sale` tinyint(1) NOT NULL DEFAULT '1',
  `discount_price` decimal(15,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`variant_id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `fk_product_variants_products` (`product_id`)
) ;

--
-- Đang đổ dữ liệu cho bảng `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `sku`, `ram`, `storage`, `color`, `sale_price`, `stock_quantity`, `is_on_sale`, `discount_price`, `created_at`, `updated_at`) VALUES
(1, 1, 'SKU-1-1', 8, 256, 'Titan đen', 29490000.00, 14, 1, NULL, '2026-11-21 07:03:14', NULL),
(2, 2, 'SKU-2-1', 12, 256, 'Titan xám', 27990000.00, 10, 1, NULL, '2026-11-21 07:03:14', NULL),
(3, 3, 'SKU-3-1', 8, 128, 'Xanh lưu ly', 19490000.00, 11, 1, NULL, '2026-11-21 07:03:14', NULL),
(4, 4, 'SKU-4-1', 8, 128, 'Xanh bạc hà', 8990000.00, 16, 1, NULL, '2026-11-21 07:03:14', NULL),
(5, 5, 'SKU-5-1', 12, 256, 'Trắng', 19990000.00, 7, 1, NULL, '2026-11-21 07:03:14', NULL),
(6, 6, 'SKU-6-1', 12, 256, 'Tím', 13490000.00, 9, 1, NULL, '2026-11-21 07:03:14', NULL),
(7, 7, 'SKU-7-1', 12, 128, 'Đen', 15990000.00, 5, 1, NULL, '2026-11-21 07:03:14', NULL),
(8, 8, 'SKU-8-1', 6, 128, 'Xanh lá', 4690000.00, 17, 1, NULL, '2026-11-21 07:03:14', NULL),
(9, 1, 'SKU-1-37c8cf', 8, 256, 'Titan đen', 29490000.00, 14, 1, NULL, '2026-11-21 07:03:14', NULL),
(10, 1, 'SKU-1-6686f3', 8, 512, 'Trắng', 31290000.00, 5, 1, NULL, '2026-11-21 07:03:14', NULL),
(11, 2, 'SKU-2-9aff96', 12, 256, 'Titan xám', 27990000.00, 10, 1, NULL, '2026-11-21 07:03:14', NULL),
(12, 2, 'SKU-2-7d3093', 12, 512, 'Đen', 29790000.00, 5, 1, NULL, '2026-11-21 07:03:14', NULL),
(13, 3, 'SKU-3-fee42b', 8, 128, 'Xanh lưu ly', 19490000.00, 11, 1, NULL, '2026-11-21 07:03:14', NULL),
(14, 3, 'SKU-3-b6826d', 8, 256, 'Trắng', 21290000.00, 5, 1, NULL, '2026-11-21 07:03:14', NULL),
(15, 4, 'SKU-4-dcc243', 8, 128, 'Xanh bạc hà', 8990000.00, 16, 1, NULL, '2026-11-21 07:03:14', NULL),
(16, 4, 'SKU-4-0acace', 8, 256, 'Đen', 10790000.00, 5, 1, NULL, '2026-11-21 07:03:14', NULL),
(17, 5, 'SKU-5-a731bf', 12, 256, 'Trắng', 19990000.00, 7, 1, NULL, '2026-11-21 07:03:14', NULL),
(18, 6, 'SKU-6-477e73', 12, 256, 'Tím', 13490000.00, 9, 1, NULL, '2026-11-21 07:03:14', NULL),
(19, 7, 'SKU-7-a3fb42', 12, 128, 'Đen', 15990000.00, 5, 1, NULL, '2026-11-21 07:03:14', NULL),
(20, 8, 'SKU-8-da7cb8', 6, 128, 'Xanh lá', 4690000.00, 17, 1, NULL, '2026-11-21 07:03:14', NULL),
(23, 11, 'SKU-11-1', 8, 128, 'Tiêu chuẩn', 50000000.00, 20, 0, NULL, '2026-11-21 07:03:14', NULL),
(28, 15, 'IP17PM-256-orange', 8, 256, 'Cam vu tru', 33990000.00, 10, 1, NULL, '2026-11-25 15:21:45', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `order_detail_id` int UNSIGNED DEFAULT NULL,
  `rating` int NOT NULL,
  `content` text,
  `status` varchar(40) NOT NULL DEFAULT 'cho_duyet',
  `reviewed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  UNIQUE KEY `uq_user_product_review` (`user_id`,`product_id`),
  KEY `fk_reviews_users` (`user_id`),
  KEY `fk_reviews_products` (`product_id`),
  KEY `fk_reviews_order_detail` (`order_detail_id`)
) ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int UNSIGNED NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'hoat_dong',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `address` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_roles` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `order_assignments`
--
ALTER TABLE `order_assignments`
  ADD CONSTRAINT `fk_assignments_assigned_by` FOREIGN KEY (`assigned_by_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_assignments_employee` FOREIGN KEY (`employee_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_order_assignments_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `fk_order_details_variants` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`);

--
-- Các ràng buộc cho bảng `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `fk_order_history_updated_by` FOREIGN KEY (`updated_by_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_order_status_history_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Các ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_product_variants_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_order_detail` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`order_detail_id`),
  ADD CONSTRAINT `fk_reviews_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `fk_reviews_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
