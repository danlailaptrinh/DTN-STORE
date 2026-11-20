-- Import moi vao MySQL 8.0.16+; chi dung khi dtd_shop chua ton tai hoac rong.
-- Khong xoa database/bang cu. Giu du lieu goc.
CREATE DATABASE IF NOT EXISTS `dtd_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `dtd_shop`;

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
-- Cơ sở dữ liệu: `dtd_shop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bien_the_san_pham`
--

CREATE TABLE `bien_the_san_pham` (
  `id_bien_the` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_san_pham` int UNSIGNED NOT NULL,
  `sku` varchar(100) NOT NULL,
  `ram` int UNSIGNED NOT NULL,
  `bo_nho` int UNSIGNED NOT NULL,
  `mau_sac` varchar(80) NOT NULL,
  `gia_ban` decimal(15,2) NOT NULL,
  `so_luong_ton` int UNSIGNED NOT NULL DEFAULT '0',
  `dang_ban` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_bien_the`),
  UNIQUE KEY `sku` (`sku`),
  KEY `fk_bien_the_san_pham_san_pham` (`id_san_pham`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `bien_the_san_pham`
--

INSERT INTO `bien_the_san_pham` (`id_bien_the`, `id_san_pham`, `sku`, `ram`, `bo_nho`, `mau_sac`, `gia_ban`, `so_luong_ton`, `dang_ban`) VALUES
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
-- Cấu trúc bảng cho bảng `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `id_chi_tiet_don` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_don_hang` int UNSIGNED NOT NULL,
  `id_bien_the` int UNSIGNED NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `cau_hinh` varchar(255) NOT NULL,
  `don_gia` decimal(15,2) NOT NULL,
  `so_luong` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_chi_tiet_don`),
  KEY `fk_chi_tiet_don_hang_don_hang` (`id_don_hang`),
  KEY `fk_chi_tiet_don_hang_bien_the` (`id_bien_the`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_gia`
--

CREATE TABLE `danh_gia` (
  `id_danh_gia` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_nguoi_dung` int UNSIGNED NOT NULL,
  `id_san_pham` int UNSIGNED NOT NULL,
  `id_chi_tiet_don` int UNSIGNED DEFAULT NULL,
  `so_sao` int NOT NULL,
  `noi_dung` text,
  `trang_thai` varchar(40) NOT NULL DEFAULT 'cho_duyet',
  `ngay_danh_gia` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_danh_gia`),
  KEY `fk_danh_gia_nguoi_dung` (`id_nguoi_dung`),
  KEY `fk_danh_gia_san_pham` (`id_san_pham`),
  KEY `fk_danh_gia_chi_tiet_don` (`id_chi_tiet_don`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `id_danh_muc` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ten_danh_muc` varchar(150) NOT NULL,
  `duong_dan` varchar(200) NOT NULL,
  `hien_thi` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_danh_muc`),
  UNIQUE KEY `duong_dan` (`duong_dan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`id_danh_muc`, `ten_danh_muc`, `duong_dan`, `hien_thi`) VALUES
(1, 'Apple', 'apple', 1),
(2, 'Samsung', 'samsung', 1),
(3, 'Xiaomi', 'xiaomi', 1),
(4, 'OPPO', 'oppo', 1),
(5, 'Google', 'google', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `id_don_hang` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_nguoi_dung` int UNSIGNED NOT NULL,
  `ma_don` varchar(50) NOT NULL,
  `ten_nguoi_nhan` varchar(150) NOT NULL,
  `so_dien_thoai_nhan` varchar(20) NOT NULL,
  `dia_chi_giao_hang` text NOT NULL,
  `tong_tien` decimal(15,2) NOT NULL DEFAULT '0.00',
  `trang_thai` varchar(40) NOT NULL DEFAULT 'cho_xac_nhan',
  `ngay_dat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_don_hang`),
  UNIQUE KEY `ma_don` (`ma_don`),
  KEY `fk_don_hang_nguoi_dung` (`id_nguoi_dung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_don_hang`
--

CREATE TABLE `lich_su_don_hang` (
  `id_lich_su` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_don_hang` int UNSIGNED NOT NULL,
  `id_nguoi_cap_nhat` int UNSIGNED NOT NULL,
  `trang_thai_cu` varchar(40) DEFAULT NULL,
  `trang_thai_moi` varchar(40) NOT NULL,
  `ghi_chu` text,
  `ngay_cap_nhat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_lich_su`),
  KEY `fk_lich_su_don_hang_don_hang` (`id_don_hang`),
  KEY `fk_lich_su_don_hang_nguoi_cap_nhat` (`id_nguoi_cap_nhat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id_nguoi_dung` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_vai_tro` int UNSIGNED NOT NULL,
  `ho_ten` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT 'hoat_dong',
  `ngay_tao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_nguoi_dung`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_nguoi_dung_vai_tro` (`id_vai_tro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phan_cong_don`
--

CREATE TABLE `phan_cong_don` (
  `id_phan_cong` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_don_hang` int UNSIGNED NOT NULL,
  `id_nhan_vien` int UNSIGNED NOT NULL,
  `id_nguoi_phan_cong` int UNSIGNED NOT NULL,
  `ngay_phan_cong` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_ket_thuc` datetime DEFAULT NULL,
  `trang_thai` varchar(40) NOT NULL DEFAULT 'dang_xu_ly',
  PRIMARY KEY (`id_phan_cong`),
  KEY `fk_phan_cong_don_don_hang` (`id_don_hang`),
  KEY `fk_phan_cong_don_nhan_vien` (`id_nhan_vien`),
  KEY `fk_phan_cong_don_nguoi_phan_cong` (`id_nguoi_phan_cong`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `id_san_pham` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_danh_muc` int UNSIGNED NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `thuong_hieu` varchar(120) NOT NULL,
  `mo_ta` text,
  `danh_sach_anh` json DEFAULT NULL,
  `trang_thai` varchar(30) NOT NULL DEFAULT 'dang_ban',
  `ngay_them` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gia_niem_yet` decimal(15,2) DEFAULT NULL,
  `gia_cu` decimal(15,2) DEFAULT NULL,
  `man_hinh` varchar(50) DEFAULT NULL,
  `chip` varchar(100) DEFAULT NULL,
  `nhan_uu_dai` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id_san_pham`),
  KEY `fk_san_pham_danh_muc` (`id_danh_muc`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`id_san_pham`, `id_danh_muc`, `ten_san_pham`, `thuong_hieu`, `mo_ta`, `danh_sach_anh`, `trang_thai`, `ngay_them`, `gia_niem_yet`, `gia_cu`, `man_hinh`, `chip`, `nhan_uu_dai`) VALUES
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
-- Cấu trúc bảng cho bảng `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `id_thanh_toan` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_don_hang` int UNSIGNED NOT NULL,
  `phuong_thuc` varchar(30) NOT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `trang_thai` varchar(40) NOT NULL DEFAULT 'cho_thanh_toan',
  `noi_dung_qr` text,
  `ma_giao_dich` varchar(150) DEFAULT NULL,
  `ngay_thanh_toan` datetime DEFAULT NULL,
  PRIMARY KEY (`id_thanh_toan`),
  UNIQUE KEY `ma_giao_dich` (`ma_giao_dich`),
  KEY `fk_thanh_toan_don_hang` (`id_don_hang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vai_tro`
--

CREATE TABLE `vai_tro` (
  `id_vai_tro` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ten_vai_tro` varchar(50) NOT NULL,
  `mo_ta` text,
  PRIMARY KEY (`id_vai_tro`),
  UNIQUE KEY `ten_vai_tro` (`ten_vai_tro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bien_the_san_pham`
--
ALTER TABLE `bien_the_san_pham`
  ADD CONSTRAINT `fk_bien_the_san_pham_san_pham` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id_san_pham`);

--
-- Các ràng buộc cho bảng `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `fk_chi_tiet_don_hang_bien_the` FOREIGN KEY (`id_bien_the`) REFERENCES `bien_the_san_pham` (`id_bien_the`),
  ADD CONSTRAINT `fk_chi_tiet_don_hang_don_hang` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id_don_hang`);

--
-- Các ràng buộc cho bảng `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `fk_danh_gia_chi_tiet_don` FOREIGN KEY (`id_chi_tiet_don`) REFERENCES `chi_tiet_don_hang` (`id_chi_tiet_don`),
  ADD CONSTRAINT `fk_danh_gia_nguoi_dung` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id_nguoi_dung`),
  ADD CONSTRAINT `fk_danh_gia_san_pham` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id_san_pham`);

--
-- Các ràng buộc cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `fk_don_hang_nguoi_dung` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id_nguoi_dung`);

--
-- Các ràng buộc cho bảng `lich_su_don_hang`
--
ALTER TABLE `lich_su_don_hang`
  ADD CONSTRAINT `fk_lich_su_don_hang_don_hang` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id_don_hang`),
  ADD CONSTRAINT `fk_lich_su_don_hang_nguoi_cap_nhat` FOREIGN KEY (`id_nguoi_cap_nhat`) REFERENCES `nguoi_dung` (`id_nguoi_dung`);

--
-- Các ràng buộc cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `fk_nguoi_dung_vai_tro` FOREIGN KEY (`id_vai_tro`) REFERENCES `vai_tro` (`id_vai_tro`);

--
-- Các ràng buộc cho bảng `phan_cong_don`
--
ALTER TABLE `phan_cong_don`
  ADD CONSTRAINT `fk_phan_cong_don_don_hang` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id_don_hang`),
  ADD CONSTRAINT `fk_phan_cong_don_nguoi_phan_cong` FOREIGN KEY (`id_nguoi_phan_cong`) REFERENCES `nguoi_dung` (`id_nguoi_dung`),
  ADD CONSTRAINT `fk_phan_cong_don_nhan_vien` FOREIGN KEY (`id_nhan_vien`) REFERENCES `nguoi_dung` (`id_nguoi_dung`);

--
-- Các ràng buộc cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `fk_san_pham_danh_muc` FOREIGN KEY (`id_danh_muc`) REFERENCES `danh_muc` (`id_danh_muc`);

--
-- Các ràng buộc cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD CONSTRAINT `fk_thanh_toan_don_hang` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id_don_hang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Nang cap 23 thuoc tinh
ALTER TABLE `nguoi_dung`
  ADD COLUMN `dia_chi` VARCHAR(255) DEFAULT NULL,
  ADD COLUMN `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `danh_muc`
  ADD COLUMN `mo_ta` TEXT,
  ADD COLUMN `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `san_pham`
  ADD COLUMN `duong_dan` VARCHAR(220) DEFAULT NULL,
  ADD COLUMN `thong_so_ky_thuat` TEXT,
  ADD COLUMN `noi_bat` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `bien_the_san_pham`
  ADD COLUMN `gia_khuyen_mai` DECIMAL(15,2) DEFAULT NULL,
  ADD COLUMN `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `don_hang`
  ADD COLUMN `ghi_chu` TEXT,
  ADD COLUMN `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `chi_tiet_don_hang`
  ADD COLUMN `ten_danh_muc` VARCHAR(150) DEFAULT NULL,
  ADD COLUMN `anh_san_pham` VARCHAR(255) DEFAULT NULL,
  ADD COLUMN `thanh_tien` DECIMAL(25,2) GENERATED ALWAYS AS (`don_gia` * `so_luong`) STORED;

ALTER TABLE `phan_cong_don`
  ADD COLUMN `ghi_chu` VARCHAR(255) DEFAULT NULL;

ALTER TABLE `thanh_toan`
  ADD COLUMN `mo_phong` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN `ghi_chu` VARCHAR(255) DEFAULT NULL,
  ADD COLUMN `ngay_tao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `danh_gia`
  ADD COLUMN `ngay_cap_nhat` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

-- Tao slug duy nhat cho cac san pham cu, khong doi ten san pham.
UPDATE `san_pham`
SET `duong_dan` = CONCAT(TRIM(BOTH '-' FROM REGEXP_REPLACE(LOWER(`ten_san_pham`), '[^a-z0-9]+', '-')), '-', `id_san_pham`), `ngay_cap_nhat` = `ngay_cap_nhat`
WHERE `duong_dan` IS NULL;
ALTER TABLE `san_pham` ADD UNIQUE KEY `uq_san_pham_duong_dan` (`duong_dan`);
ALTER TABLE `bien_the_san_pham` ADD CONSTRAINT `chk_gia_khuyen_mai` CHECK (`gia_khuyen_mai` IS NULL OR (`gia_khuyen_mai` >= 0 AND `gia_khuyen_mai` < `gia_ban`));
ALTER TABLE `chi_tiet_don_hang` ADD CONSTRAINT `chk_so_luong_mua` CHECK (`so_luong` > 0);
ALTER TABLE `danh_gia` ADD CONSTRAINT `chk_so_sao` CHECK (`so_sao` BETWEEN 1 AND 5), ADD UNIQUE KEY `uq_danh_gia_nguoi_san_pham` (`id_nguoi_dung`, `id_san_pham`);
-- Chi mot phan cong dang xu ly cho moi don; cac phan cong cu khong bi gioi han.
CREATE UNIQUE INDEX `uq_phan_cong_dang_xu_ly` ON `phan_cong_don` ((CASE WHEN `trang_thai` = 'dang_xu_ly' THEN `id_don_hang` ELSE NULL END));
-- CHUA them UNIQUE cho to hop bien the vi du lieu goc co 8 cap trung.
-- Kiem tra truoc khi xu ly thu cong (khong xoa du lieu):
SELECT `id_san_pham`, `ram`, `bo_nho`, `mau_sac`, COUNT(*) AS `so_luong`, GROUP_CONCAT(`id_bien_the` ORDER BY `id_bien_the`) AS `cac_id`
FROM `bien_the_san_pham` GROUP BY `id_san_pham`, `ram`, `bo_nho`, `mau_sac` HAVING COUNT(*) > 1;
-- Sau khi xu ly trung va cap nhat tham chieu, moi chay:
-- ALTER TABLE `bien_the_san_pham` ADD UNIQUE KEY `uq_cau_hinh` (`id_san_pham`, `ram`, `bo_nho`, `mau_sac`);
