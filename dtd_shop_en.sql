-- DTD SHOP ENGLISH SCHEMA: translated from dtd_shop.sql.
-- 11 tables, same row data and JSON, 23 added columns, translated identifiers.
-- Status values (e.g. dang_ban, cho_duyet) intentionally preserved for existing app logic.
-- Import into a NEW empty MySQL 8.0.16+ database named dtd_shop_en.
-- This file does not modify dtd_shop.

-- Import moi vao MySQL 8.0.16+; chi dung khi dtd_shop chua ton tai hoac rong.
-- Khong xoa database/bang cu. Giu du lieu goc.
CREATE DATABASE IF NOT EXISTS `dtd_shop_en` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `dtd_shop_en`;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th10 18, 2026 lúc 07:31 AM
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
-- Cấu trúc bảng cho bảng `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `sku` varchar(100) NOT NULL,
  `ram` int UNSIGNED NOT NULL,
  `storage` int UNSIGNED NOT NULL,
  `color` varchar(80) NOT NULL,
  `sale_price` decimal(15,2) NOT NULL,
  `stock_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `is_on_sale` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`variant_id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `fk_product_variants_products` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `sku`, `ram`, `storage`, `color`, `sale_price`, `stock_quantity`, `is_on_sale`) VALUES
(1, 1, 'SKU-1-1', 8, 256, 'Titan đen', 29490000.00, 14, 1),
(2, 2, 'SKU-2-1', 12, 256, 'Titan xám', 27990000.00, 10, 1),
(3, 3, 'SKU-3-1', 8, 128, 'Xanh lưu ly', 19490000.00, 11, 1),
(4, 4, 'SKU-4-1', 8, 128, 'Xanh bạc hà', 8990000.00, 16, 1),
(5, 5, 'SKU-5-1', 12, 256, 'Trắng', 19990000.00, 7, 1),
(6, 6, 'SKU-6-1', 12, 256, 'Tím', 13490000.00, 9, 1),
(7, 7, 'SKU-7-1', 12, 128, 'Đen', 15990000.00, 5, 1),
(8, 8, 'SKU-8-1', 6, 128, 'Xanh lá', 4690000.00, 17, 1),
(9, 1, 'SKU-1-37c8cf', 8, 256, 'Titan đen', 29490000.00, 14, 1),
(10, 1, 'SKU-1-6686f3', 8, 512, 'Trắng', 31290000.00, 5, 1),
(11, 2, 'SKU-2-9aff96', 12, 256, 'Titan xám', 27990000.00, 10, 1),
(12, 2, 'SKU-2-7d3093', 12, 512, 'Đen', 29790000.00, 5, 1),
(13, 3, 'SKU-3-fee42b', 8, 128, 'Xanh lưu ly', 19490000.00, 11, 1),
(14, 3, 'SKU-3-b6826d', 8, 256, 'Trắng', 21290000.00, 5, 1),
(15, 4, 'SKU-4-dcc243', 8, 128, 'Xanh bạc hà', 8990000.00, 16, 1),
(16, 4, 'SKU-4-0acace', 8, 256, 'Đen', 10790000.00, 5, 1),
(17, 5, 'SKU-5-a731bf', 12, 256, 'Trắng', 19990000.00, 7, 1),
(18, 6, 'SKU-6-477e73', 12, 256, 'Tím', 13490000.00, 9, 1),
(19, 7, 'SKU-7-a3fb42', 12, 128, 'Đen', 15990000.00, 5, 1),
(20, 8, 'SKU-8-da7cb8', 6, 128, 'Xanh lá', 4690000.00, 17, 1),
(23, 11, 'SKU-11-1', 8, 128, 'Tiêu chuẩn', 50000000.00, 20, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `variant_id` int UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `configuration` varchar(255) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  PRIMARY KEY (`order_detail_id`),
  KEY `fk_order_details_orders` (`order_id`),
  KEY `fk_order_details_variants` (`variant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `order_detail_id` int UNSIGNED DEFAULT NULL,
  `rating` int NOT NULL,
  `content` text,
  `status` varchar(40) NOT NULL DEFAULT 'cho_duyet',
  `reviewed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `fk_reviews_users` (`user_id`),
  KEY `fk_reviews_products` (`product_id`),
  KEY `fk_reviews_order_detail` (`order_detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `category_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_name` varchar(150) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `slug`, `is_visible`) VALUES
(1, 'Apple', 'apple', 1),
(2, 'Samsung', 'samsung', 1),
(3, 'Xiaomi', 'xiaomi', 1),
(4, 'OPPO', 'oppo', 1),
(5, 'Google', 'google', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `recipient_name` varchar(150) NOT NULL,
  `recipient_phone` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(40) NOT NULL DEFAULT 'cho_xac_nhan',
  `ordered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_code` (`order_code`),
  KEY `fk_orders_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_status_history`
--

CREATE TABLE `order_status_history` (
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
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int UNSIGNED NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'hoat_dong',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_roles` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_assignments`
--

CREATE TABLE `order_assignments` (
  `assignment_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `employee_id` int UNSIGNED NOT NULL,
  `assigned_by_id` int UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ended_at` datetime DEFAULT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'dang_xu_ly',
  PRIMARY KEY (`assignment_id`),
  KEY `fk_order_assignments_orders` (`order_id`),
  KEY `fk_assignments_employee` (`employee_id`),
  KEY `fk_assignments_assigned_by` (`assigned_by_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
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
  PRIMARY KEY (`product_id`),
  KEY `fk_products_categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `brand`, `description`, `image_list`, `status`, `added_at`, `list_price`, `old_price`, `screen`, `chip`, `promotion_label`) VALUES
(1, 1, 'iPhone 16 Pro Max', 'Apple', 'Sản phẩm iPhone 16 Pro Max chính hãng, màn hình 6,9″, vi xử lý A18 Pro. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-1.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 29490000.00, 34990000.00, '6,9″', 'A18 Pro', 'Bán chạy'),
(2, 2, 'Samsung Galaxy S25 Ultra', 'Samsung', 'Sản phẩm Samsung Galaxy S25 Ultra chính hãng, màn hình 6,9″, vi xử lý Snapdragon 8 Elite. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-2.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 27990000.00, 33990000.00, '6,9″', 'Snapdragon 8 Elite', 'Giảm 17%'),
(3, 1, 'iPhone 16', 'Apple', 'Sản phẩm iPhone 16 chính hãng, màn hình 6,1″, vi xử lý A18. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-3.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 19490000.00, 22990000.00, '6,1″', 'A18', ''),
(4, 2, 'Samsung Galaxy A56 5G', 'Samsung', 'Sản phẩm Samsung Galaxy A56 5G chính hãng, màn hình 6,7″, vi xử lý Exynos 1580. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-4.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 8990000.00, 9990000.00, '6,7″', 'Exynos 1580', 'Trả góp 0%'),
(5, 3, 'Xiaomi 15', 'Xiaomi', 'Sản phẩm Xiaomi 15 chính hãng, màn hình 6,36″, vi xử lý Snapdragon 8 Elite. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-5.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 19990000.00, 22990000.00, '6,36″', 'Snapdragon 8 Elite', ''),
(6, 4, 'OPPO Reno13 5G', 'OPPO', 'Sản phẩm OPPO Reno13 5G chính hãng, màn hình 6,59″, vi xử lý Dimensity 8350. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-6.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 13490000.00, 15990000.00, '6,59″', 'Dimensity 8350', ''),
(7, 5, 'Google Pixel 9', 'Google', 'Sản phẩm Google Pixel 9 chính hãng, màn hình 6,3″, vi xử lý Google Tensor G4. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-7.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 15990000.00, 18990000.00, '6,3″', 'Google Tensor G4', ''),
(8, 3, 'Xiaomi Redmi Note 14', 'Xiaomi', 'Sản phẩm Xiaomi Redmi Note 14 chính hãng, màn hình 6,67″, vi xử lý Helio G99-Ultra. Bảo hành 12 tháng tại hệ thống XSHOP toàn quốc.', '[\"phone-8.webp\"]', 'dang_ban', '2026-11-18 13:19:37', 4690000.00, 5290000.00, '6,67″', 'Helio G99-Ultra', ''),
(11, 1, 'iphone duo', 'Apple', '', '[\"uploads/7b144db29a7482f7dcff9668620f6dbe.png\"]', 'ngung_ban', '2026-11-18 13:32:28', 50000000.00, 60000000.00, '7.6', 'a18', 'bán chạy');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `payment_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `method` varchar(30) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` varchar(40) NOT NULL DEFAULT 'cho_thanh_toan',
  `qr_content` text,
  `transaction_code` varchar(150) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `transaction_code` (`transaction_code`),
  KEY `fk_payments_orders` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `role_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_product_variants_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_variants` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`),
  ADD CONSTRAINT `fk_order_details_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_order_detail` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`order_detail_id`),
  ADD CONSTRAINT `fk_reviews_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_reviews_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `fk_order_status_history_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `fk_order_history_updated_by` FOREIGN KEY (`updated_by_id`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Các ràng buộc cho bảng `order_assignments`
--
ALTER TABLE `order_assignments`
  ADD CONSTRAINT `fk_order_assignments_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `fk_assignments_assigned_by` FOREIGN KEY (`assigned_by_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_assignments_employee` FOREIGN KEY (`employee_id`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Nang cap 23 thuoc tinh
ALTER TABLE `users`
  ADD COLUMN `address` VARCHAR(255) DEFAULT NULL,
  ADD COLUMN `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `categories`
  ADD COLUMN `description` TEXT,
  ADD COLUMN `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `products`
  ADD COLUMN `slug` VARCHAR(220) DEFAULT NULL,
  ADD COLUMN `technical_specs` TEXT,
  ADD COLUMN `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `product_variants`
  ADD COLUMN `discount_price` DECIMAL(15,2) DEFAULT NULL,
  ADD COLUMN `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `orders`
  ADD COLUMN `note` TEXT,
  ADD COLUMN `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `order_details`
  ADD COLUMN `category_name` VARCHAR(150) DEFAULT NULL,
  ADD COLUMN `product_image` VARCHAR(255) DEFAULT NULL,
  ADD COLUMN `line_total` DECIMAL(25,2) GENERATED ALWAYS AS (`unit_price` * `quantity`) STORED;

ALTER TABLE `order_assignments`
  ADD COLUMN `note` VARCHAR(255) DEFAULT NULL;

ALTER TABLE `payments`
  ADD COLUMN `is_simulated` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN `note` VARCHAR(255) DEFAULT NULL,
  ADD COLUMN `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `reviews`
  ADD COLUMN `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

-- Tao slug duy nhat cho cac san pham cu, khong doi ten san pham.
UPDATE `products`
SET `slug` = CONCAT(TRIM(BOTH '-' FROM REGEXP_REPLACE(LOWER(`product_name`), '[^a-z0-9]+', '-')), '-', `product_id`), `updated_at` = `updated_at`
WHERE `slug` IS NULL;
ALTER TABLE `products` ADD UNIQUE KEY `uq_products_slug` (`slug`);
ALTER TABLE `product_variants` ADD CONSTRAINT `chk_discount_price` CHECK (`discount_price` IS NULL OR (`discount_price` >= 0 AND `discount_price` < `sale_price`));
ALTER TABLE `order_details` ADD CONSTRAINT `chk_order_quantity` CHECK (`quantity` > 0);
ALTER TABLE `reviews` ADD CONSTRAINT `chk_review_rating` CHECK (`rating` BETWEEN 1 AND 5), ADD UNIQUE KEY `uq_user_product_review` (`user_id`, `product_id`);
-- Chi mot phan cong dang xu ly cho moi don; cac phan cong cu khong bi gioi han.
CREATE UNIQUE INDEX `uq_active_order_assignment` ON `order_assignments` ((CASE WHEN `status` = 'dang_xu_ly' THEN `order_id` ELSE NULL END));
-- CHUA them UNIQUE cho to hop bien the vi du lieu goc co 8 cap trung.
-- Kiem tra truoc khi xu ly thu cong (khong xoa du lieu):
SELECT `product_id`, `ram`, `storage`, `color`, COUNT(*) AS `quantity`, GROUP_CONCAT(`variant_id` ORDER BY `variant_id`) AS `cac_id`
FROM `product_variants` GROUP BY `product_id`, `ram`, `storage`, `color` HAVING COUNT(*) > 1;
-- Sau khi xu ly trung va cap nhat tham chieu, moi chay:
-- ALTER TABLE `product_variants` ADD UNIQUE KEY `uq_variant_configuration` (`product_id`, `ram`, `storage`, `color`);
