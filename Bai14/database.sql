-- =======================================================
-- CƠ SỞ DỮ LIỆU: laptop_shop (Bài 14 - Admin Administration)
-- =======================================================

CREATE DATABASE IF NOT EXISTS `laptop_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `laptop_shop`;

-- 1. Bảng Quản Trị Viên (admins)
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
    `admin_id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `fullname` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `role` VARCHAR(20) NOT NULL DEFAULT 'admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm tài khoản quản trị mặc định:
-- Username: admin
-- Mật khẩu: admin123 (mã hóa chuẩn password_hash BCRYPT)
INSERT INTO `admins` (`admin_id`, `username`, `password`, `fullname`, `email`, `role`) VALUES
(1, 'admin', '$2y$10$IooDw5P.Jx2.rlYw9hEUt.Z1LciEAZHtgXmIK4K7ZNaH.eYOzbqAm', 'Quản Trị Viên Hệ Thống', 'admin@laptopshop.vn', 'superadmin'),
(2, 'manager', '$2y$10$IooDw5P.Jx2.rlYw9hEUt.Z1LciEAZHtgXmIK4K7ZNaH.eYOzbqAm', 'Quản Lý Kho Hàng', 'manager@laptopshop.vn', 'admin');

-- 2. Bảng Danh Mục Hãng Laptop (categories)
CREATE TABLE IF NOT EXISTS `categories` (
    `category_id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng Sản Phẩm Laptop (products)
CREATE TABLE IF NOT EXISTS `products` (
    `product_id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `price` DECIMAL(12, 0) NOT NULL DEFAULT 0,
    `old_price` DECIMAL(12, 0) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT 'laptop_default.png',
    `summary_spec` TEXT NOT NULL,
    `full_spec` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_category_b14` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
