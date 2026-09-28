-- =======================================================
-- CƠ SỞ DỮ LIỆU: laptop_shop (Bài 15 - Xây Dựng Giỏ Hàng Shopping Cart)
-- =======================================================

CREATE DATABASE IF NOT EXISTS `laptop_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `laptop_shop`;

-- 1. Bảng Danh Mục Hãng Laptop (categories)
CREATE TABLE IF NOT EXISTS `categories` (
    `category_id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bảng Sản Phẩm Laptop (products)
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
    CONSTRAINT `fk_products_category_b15` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng Đơn Đặt Hàng (orders)
CREATE TABLE IF NOT EXISTS `orders` (
    `order_id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_phone` VARCHAR(20) NOT NULL,
    `customer_email` VARCHAR(100) DEFAULT NULL,
    `customer_address` VARCHAR(255) NOT NULL,
    `order_notes` TEXT DEFAULT NULL,
    `payment_method` VARCHAR(50) NOT NULL DEFAULT 'COD',
    `total_amount` DECIMAL(12, 0) NOT NULL DEFAULT 0,
    `status` VARCHAR(30) NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Bảng Chi Tiết Đơn Hàng (order_details)
CREATE TABLE IF NOT EXISTS `order_details` (
    `detail_id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `price` DECIMAL(12, 0) NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `subtotal` DECIMAL(12, 0) NOT NULL,
    CONSTRAINT `fk_order_details_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu ban đầu cho Hãng (Nếu chưa có)
INSERT IGNORE INTO `categories` (`category_id`, `category_name`, `description`) VALUES
(1, 'Laptop DELL', 'Thương hiệu bền bỉ, hiệu năng cao hàng đầu thế giới'),
(2, 'Laptop ASUS', 'Thiết kế thời trang, dòng ROG & TUF gaming đỉnh cao'),
(3, 'Laptop HP', 'Dòng Envy, Spectre, Pavilion cao cấp sang trọng'),
(4, 'Laptop LENOVO', 'Bàn phím tuyệt đỉnh ThinkPad, Legion gaming cực mạnh'),
(5, 'Laptop ACER', 'Hiệu năng trên giá thành tối ưu: Nitro, Swift, Predator'),
(6, 'Laptop MACBOOK', 'Hệ điều hành macOS mượt mà, chip Apple Silicon M-series'),
(7, 'Laptop SONY VAIO', 'Đẳng cấp Nhật Bản, mỏng nhẹ thời thượng'),
(8, 'Laptop SAMSUNG', 'Màn hình AMOLED tuyệt đẹp, Galaxy Book siêu mỏng');
