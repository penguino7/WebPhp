-- =======================================================
-- CƠ SỞ DỮ LIỆU: laptop_shop (Bài 13 - Web Bán Laptop)
-- =======================================================

CREATE DATABASE IF NOT EXISTS `laptop_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `laptop_shop`;

-- 1. Bảng Danh mục Hãng Laptop (categories)
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
    `category_id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bảng Sản phẩm Laptop (products)
CREATE TABLE `products` (
    `product_id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `price` DECIMAL(12, 0) NOT NULL DEFAULT 0,
    `old_price` DECIMAL(12, 0) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT 'laptop_default.png',
    `summary_spec` TEXT NOT NULL,
    `full_spec` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- DỮ LIỆU MẪU (Sample Data)
-- -------------------------------------------------------

-- Thêm các Hãng laptop (Categories)
INSERT INTO `categories` (`category_id`, `category_name`, `description`) VALUES
(1, 'Laptop DELL', 'Dòng laptop bền bỉ, hiệu năng cao từ thương hiệu DELL'),
(2, 'Laptop HP - Compaq', 'Thiết kế sang trọng, thanh lịch cho doanh nhân và văn phòng'),
(3, 'Laptop SONY VAIO', 'Dòng sản phẩm cao cấp, tinh tế, màn hình sắc nét đỉnh cao'),
(4, 'Laptop LENOVO', 'Đỉnh cao bàn phím ThinkPad, đa dụng cho học sinh, văn phòng'),
(5, 'Laptop ACER', 'Thiết kế trẻ trung, cấu hình mạnh mẽ giá phổ thông'),
(6, 'Laptop ASUS', 'Tiên phong công nghệ màn hình OLED, mỏng nhẹ thời thượng'),
(7, 'Laptop SAMSUNG', 'Màn hình hiển thị rực rỡ, tích hợp hoàn hảo hệ sinh thái Galaxy'),
(8, 'Laptop MACBOOK (Apple)', 'Đẳng cấp Apple Silicon M-Series, pin trâu, hoàn thiện kim loại nguyên khối');

-- Thêm Sản phẩm Laptop (Products)
INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `price`, `old_price`, `image`, `summary_spec`, `full_spec`, `created_at`) VALUES
-- DELL
(1, 1, 'Dell Inspiron 3520 (i5 1235U/16GB/512GB/120Hz)', 14990000, 16990000, 'dell_inspiron_3520.png', 
 'CPU: Intel Core i5-1235U | RAM: 16GB DDR4 | SSD: 512GB NVMe | Màn hình: 15.6" FHD 120Hz | VGA: Intel Iris Xe', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core i5-1235U (10 nhân, 12 luồng, Turbo Boost 4.4GHz)</li><li><strong>RAM:</strong> 16GB DDR4 3200MHz (2 khe nâng cấp max 64GB)</li><li><strong>Ổ cứng:</strong> 512GB M.2 PCIe NVMe SSD</li><li><strong>Màn hình:</strong> 15.6 inch FHD (1920x1080), 120Hz, Anti-glare 250 nits</li><li><strong>Card đồ họa:</strong> Intel Iris Xe Graphics</li><li><strong>Cổng kết nối:</strong> 2x USB 3.2, 1x USB 2.0, 1x HDMI 1.4, Jack 3.5mm, SD Card Reader</li><li><strong>Trọng lượng:</strong> 1.65 kg - Pin: 3 Cell 41Wh</li><li><strong>Hệ điều hành:</strong> Windows 11 Home bản quyền + Office</li></ul>', '2026-09-01 10:00:00'),

(2, 1, 'Dell XPS 13 Plus 9320 (i7 1360P/32GB/1TB/OLED 3.5K)', 48500000, 52900000, 'dell_xps_13.png', 
 'CPU: Intel Core i7-1360P | RAM: 32GB LPDDR5 | SSD: 1TB NVMe | Màn hình: 13.4" 3.5K OLED Touch | VGA: Iris Xe', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core i7-1360P thế hệ 13 (12 nhân 16 luồng, max 5.0GHz)</li><li><strong>RAM:</strong> 32GB LPDDR5 6000MHz Onboard</li><li><strong>Ổ cứng:</strong> 1TB PCIe 4.0 NVMe M.2 SSD siêu tốc</li><li><strong>Màn hình:</strong> 13.4 inch 3.5K (3456x2160) OLED Touch, 400 nits, 100% DCI-P3</li><li><strong>Bàn phím:</strong> Touch bar cảm ứng vô cực, touchpad kính ẩn tàng hình</li><li><strong>Trọng lượng:</strong> 1.23 kg - Khung nhôm CNC nguyên khối</li><li><strong>Hệ điều hành:</strong> Windows 11 Pro bản quyền</li></ul>', '2026-09-02 11:30:00'),

(3, 1, 'Dell Gaming G15 5530 (i7 13650HX/16GB/512GB/RTX 4060)', 31990000, 34990000, 'dell_g15.png', 
 'CPU: Intel Core i7-13650HX | RAM: 16GB DDR5 | SSD: 512GB | Màn hình: 15.6" FHD 165Hz | VGA: RTX 4060 8GB', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core i7-13650HX (14 nhân 20 luồng, max 4.9GHz)</li><li><strong>RAM:</strong> 16GB DDR5 4800MHz</li><li><strong>VGA:</strong> NVIDIA GeForce RTX 4060 8GB GDDR6 (TGP 140W max)</li><li><strong>Màn hình:</strong> 15.6" Full HD 165Hz, 100% sRGB, G-Sync</li><li><strong>Tản nhiệt:</strong> Alienware Cooling Technology 2 quạt 4 ống đồng</li><li><strong>Trọng lượng:</strong> 2.81 kg - Pin 6-cell 86Wh</li></ul>', '2026-09-03 09:15:00'),

-- HP - COMPAQ
(4, 2, 'HP Pavilion 14-dv2073TU (i5 1235U/16GB/512GB/IPS)', 15490000, 17200000, 'hp_pavilion_14.png', 
 'CPU: Intel Core i5-1235U | RAM: 16GB DDR4 | SSD: 512GB NVMe | Màn hình: 14.0" FHD IPS | Âm thanh B&O', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core i5-1235U (10 nhân, 12 luồng, Turbo Boost 4.4GHz)</li><li><strong>RAM:</strong> 16GB DDR4 3200MHz</li><li><strong>Ổ cứng:</strong> 512GB PCIe NVMe SSD</li><li><strong>Màn hình:</strong> 14.0 inch Full HD (1920x1080) IPS, viền siêu mỏng Micro-Edge</li><li><strong>Âm thanh:</strong> Bang & Olufsen (B&O) kép sống động</li><li><strong>Vỏ máy:</strong> Nắp lưng kim loại sang trọng, trọng lượng 1.41 kg</li><li><strong>Hệ điều hành:</strong> Windows 11 Home bản quyền</li></ul>', '2026-09-04 14:20:00'),

(5, 2, 'HP Envy x360 14 2-in-1 (Core Ultra 5 125H/16GB/512GB/OLED 2.8K)', 24990000, 27500000, 'hp_envy_x360.png', 
 'CPU: Intel Core Ultra 5-125H (AI) | RAM: 16GB LPDDR5x | SSD: 512GB | Màn hình: 14" 2.8K OLED Touch 120Hz', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core Ultra 5 125H tích hợp NPU AI Boost</li><li><strong>RAM:</strong> 16GB LPDDR5x 6400MHz</li><li><strong>Màn hình:</strong> 14" 2.8K (2880 x 1800) OLED Cảm ứng xoay gập 360 độ, 120Hz, 100% DCI-P3</li><li><strong>Tính năng đặc biệt:</strong> Hỗ trợ bút cảm ứng HP Rechargeable MPP2.0 Tilt Pen</li><li><strong>Trọng lượng:</strong> 1.39 kg nhôm tái chế cao cấp</li></ul>', '2026-09-05 16:45:00'),

-- SONY VAIO
(6, 3, 'Sony VAIO SX14 All-Black Edition (i7 1260P/32GB/1TB/4K)', 42990000, 46000000, 'sony_vaio_sx14.png', 
 'CPU: Intel Core i7-1260P | RAM: 32GB LPDDR4x | SSD: 1TB NVMe | Màn hình: 14.0" Ultra HD 4K | Made in Japan', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>Xuất xứ:</strong> Sản xuất trực tiếp tại Azumino, Nhật Bản (Made in Japan)</li><li><strong>CPU:</strong> Intel Core i7-1260P 12 nhân 16 luồng</li><li><strong>RAM:</strong> 32GB LPDDR4x Onboard</li><li><strong>Màn hình:</strong> 14.0" Ultra HD 4K (3840x2160) chống chói đỉnh cao</li><li><strong>Vỏ máy:</strong> Sợi carbon cao cấp UD Carbon siêu bền, chống va đập</li><li><strong>Trọng lượng:</strong> Chỉ 1.06 kg - Bàn phím công thái học ErgoLift góc nghiêng hoàn hảo</li></ul>', '2026-09-06 08:30:00'),

(7, 3, 'Sony VAIO FE14 (Core i5 1135G7/8GB/512GB/FHD)', 12990000, 14500000, 'sony_vaio_fe14.png', 
 'CPU: Intel Core i5-1135G7 | RAM: 8GB DDR4 | SSD: 512GB | Màn hình: 14.1" FHD IPS | Loa THX Spatial Audio', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core i5-1135G7 (4 nhân 8 luồng, max 4.2GHz)</li><li><strong>RAM:</strong> 8GB DDR4 3200MHz</li><li><strong>Màn hình:</strong> 14.1 inch FHD (1920x1080) viền mỏng IPS</li><li><strong>Âm thanh:</strong> Tích hợp công nghệ âm thanh vòm THX Spatial Audio</li><li><strong>Cổng kết nối:</strong> Đầy đủ USB Type-C, USB 3.0, HDMI, LAN RJ45, khe thẻ nhớ</li></ul>', '2026-09-07 10:10:00'),

-- LENOVO
(8, 4, 'Lenovo ThinkPad X1 Carbon Gen 11 (i7 1355U/32GB/1TB/2.8K OLED)', 46990000, 51000000, 'lenovo_thinkpad_x1.png', 
 'CPU: Intel Core i7-1355U | RAM: 32GB LPDDR5 | SSD: 1TB NVMe | Màn hình: 14" 2.8K OLED 100% DCI-P3 | Siêu nhẹ 1.12kg', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>Dòng máy:</strong> Huyền thoại doanh nhân ThinkPad chuẩn quân đội MIL-STD-810H</li><li><strong>CPU:</strong> Intel Core i7-1355U vPro (10 nhân 12 luồng)</li><li><strong>RAM:</strong> 32GB LPDDR5 6400MHz</li><li><strong>Bàn phím:</strong> Bàn phím ThinkPad trứ danh có nút TrackPoint đỏ</li><li><strong>Bảo mật:</strong> Cảm biến vân tay Match-on-Chip, Camera IR nhận diện khuôn mặt</li><li><strong>Trọng lượng:</strong> 1.12 kg</li></ul>', '2026-09-08 11:00:00'),

(9, 4, 'Lenovo Legion Pro 5 16IRX9 (i9 14900HX/32GB/1TB/RTX 4070/240Hz)', 49990000, 54900000, 'lenovo_legion_5.png', 
 'CPU: Intel Core i9-14900HX | RAM: 32GB DDR5 | SSD: 1TB | Màn hình: 16" WQXGA 240Hz 500 nits | VGA: RTX 4070 8GB', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core i9-14900HX thế hệ 14 (24 nhân 32 luồng, turbo 5.8GHz)</li><li><strong>RAM:</strong> 32GB DDR5 5600MHz Dual Channel</li><li><strong>Card đồ họa:</strong> NVIDIA GeForce RTX 4070 8GB GDDR6 (140W)</li><li><strong>Màn hình:</strong> 16 inch 2.5K WQXGA (2560x1600) 240Hz, 100% sRGB, DisplayHDR 400</li><li><strong>Tản nhiệt:</strong> Legion Coldfront 5.0 với buồng hơi Vapor Chamber</li></ul>', '2026-09-09 13:40:00'),

-- ACER
(10, 5, 'Acer Nitro V 15 ANV15-51 (i5 13420H/16GB/512GB/RTX 4050/144Hz)', 21990000, 24500000, 'acer_nitro_v.png', 
 'CPU: Intel Core i5-13420H | RAM: 16GB DDR5 | SSD: 512GB NVMe | Màn hình: 15.6" FHD 144Hz | VGA: RTX 4050 6GB', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core i5-13420H (8 nhân 12 luồng, max 4.6GHz)</li><li><strong>RAM:</strong> 16GB DDR5 5200MHz</li><li><strong>VGA:</strong> NVIDIA GeForce RTX 4050 6GB GDDR6</li><li><strong>Màn hình:</strong> 15.6" FHD IPS 144Hz SlimBezel</li><li><strong>Phần mềm quản lý:</strong> NitroSense tùy chỉnh tốc độ quạt và hiệu năng 1 nút bấm</li></ul>', '2026-09-10 15:00:00'),

(11, 5, 'Acer Swift Go 14 (Core Ultra 7 155H/16GB/512GB/OLED 2.8K 90Hz)', 23990000, 26900000, 'acer_swift_go.png', 
 'CPU: Intel Core Ultra 7 155H AI | RAM: 16GB LPDDR5X | SSD: 512GB | Màn hình: 14" OLED 2.8K 90Hz 100% DCI-P3', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core Ultra 7 155H (16 nhân 22 luồng, Intel Arc Graphics)</li><li><strong>Màn hình:</strong> 14" OLED 2.8K (2880x1800) 90Hz, 500 nits, chứng nhận VESA True Black 500</li><li><strong>Trọng lượng:</strong> 1.32 kg nhôm nguyên khối siêu mỏng 14.9mm</li><li><strong>Pin:</strong> 65Wh sử dụng liên tục lên đến 10 giờ</li></ul>', '2026-09-11 17:15:00'),

-- ASUS
(12, 6, 'Asus Zenbook 14 OLED UX3405 (Core Ultra 5 125H/16GB/512GB/120Hz)', 24490000, 26990000, 'asus_zenbook_14.png', 
 'CPU: Intel Core Ultra 5 125H | RAM: 16GB LPDDR5X | SSD: 512GB | Màn hình: 14" 3K OLED 120Hz | Pin 75Wh', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core Ultra 5 125H tích hợp nhân xử lý AI Intel NPU</li><li><strong>Màn hình:</strong> 14.0" 3K (2880 x 1800) OLED 16:10, 120Hz, 0.2ms, 600 nits HDR</li><li><strong>Pin siêu trâu:</strong> Dung lượng pin 75Wh lên đến 15 giờ làm việc</li><li><strong>Bàn di chuột:</strong> NumberPad 2.0 tích hợp bàn phím số LED thông minh</li><li><strong>Trọng lượng:</strong> 1.2 kg - Mỏng 14.9 mm</li></ul>', '2026-09-12 09:00:00'),

(13, 6, 'Asus TUF Gaming A15 FA507NV (Ryzen 7 7735HS/16GB/512GB/RTX 4060)', 27990000, 30500000, 'asus_tuf_a15.png', 
 'CPU: AMD Ryzen 7 7735HS | RAM: 16GB DDR5 | SSD: 512GB | Màn hình: 15.6" FHD 144Hz 100% sRGB | RTX 4060 8GB', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> AMD Ryzen 7 7735HS (8 nhân 16 luồng, max 4.75GHz)</li><li><strong>RAM:</strong> 16GB DDR5 4800MHz</li><li><strong>VGA:</strong> NVIDIA GeForce RTX 4060 8GB GDDR6 (140W TGP có MUX Switch + Optimus)</li><li><strong>Độ bền:</strong> Đạt tiêu chuẩn độ bền quân đội Mỹ MIL-STD-810H</li><li><strong>Pin:</strong> 90Wh sạc nhanh 50% trong 30 phút</li></ul>', '2026-09-13 14:00:00'),

-- SAMSUNG
(14, 7, 'Samsung Galaxy Book4 Pro 360 (Core Ultra 7 155H/16GB/1TB/AMOLED 3K)', 38990000, 42000000, 'samsung_galaxy_book4.png', 
 'CPU: Intel Core Ultra 7 155H | RAM: 16GB LPDDR5X | SSD: 1TB | Màn hình: 16" Dynamic AMOLED 2X Touch | Kèm S-Pen', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>CPU:</strong> Intel Core Ultra 7 155H (16 nhân 22 luồng, NPU AI)</li><li><strong>Màn hình:</strong> 16" Dynamic AMOLED 2X 3K (2880 x 1800), 120Hz, kính Vision Booster chống lóa</li><li><strong>Phụ kiện:</strong> Kèm sẵn bút S-Pen thông minh vẽ và ghi chú siêu nhạy</li><li><strong>Hệ sinh thái:</strong> Đồng bộ liền mạch điện thoại Samsung Galaxy, chia sẻ Quick Share</li><li><strong>Trọng lượng:</strong> 1.66 kg</li></ul>', '2026-09-14 10:30:00'),

-- MACBOOK
(15, 8, 'Apple MacBook Air 13 M3 (8-Core CPU/10-Core GPU/16GB/512GB)', 32990000, 35990000, 'macbook_air_m3.png', 
 'Chip Apple M3 8-Core CPU & 10-Core GPU | RAM: 16GB Unified | SSD: 512GB | Màn hình: 13.6" Liquid Retina | Pin 18 giờ', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>Chip:</strong> Apple M3 chip với 8-core CPU, 10-core GPU, 16-core Neural Engine</li><li><strong>RAM:</strong> 16GB Unified Memory siêu tốc</li><li><strong>Ổ cứng:</strong> 512GB SSD NVMe tốc độ cao</li><li><strong>Màn hình:</strong> 13.6-inch Liquid Retina Display (2560x1664), 500 nits, Wide Color P3, True Tone</li><li><strong>Cổng sạc:</strong> MagSafe 3 sạc nam châm an toàn + 2 cổng Thunderbolt / USB 4</li><li><strong>Trọng lượng:</strong> 1.24 kg - Thời lượng pin lên đến 18 giờ</li><li><strong>Hệ điều hành:</strong> macOS Sequoia</li></ul>', '2026-09-15 08:00:00'),

(16, 8, 'Apple MacBook Pro 14 M3 Pro (11-Core CPU/14-Core GPU/18GB/512GB Space Black)', 49990000, 54500000, 'macbook_pro_14.png', 
 'Chip Apple M3 Pro | RAM: 18GB Unified | SSD: 512GB | Màn hình: 14.2" Liquid Retina XDR 120Hz Promotion | Màu Space Black', 
 '<h3>Thông số kỹ thuật chi tiết:</h3><ul><li><strong>Chip:</strong> Apple M3 Pro (11 nhân CPU, 14 nhân GPU, hỗ trợ Ray Tracing phần cứng)</li><li><strong>RAM:</strong> 18GB Unified Memory</li><li><strong>Màn hình:</strong> 14.2 inch Liquid Retina XDR (3024x1964), 120Hz ProMotion, 1600 nits Peak Brightness</li><li><strong>Cổng kết nối:</strong> 3x Thunderbolt 4, 1x HDMI, 1x SDXC Card Slot, 1x Jack 3.5mm, 1x MagSafe 3</li><li><strong>Trọng lượng:</strong> 1.61 kg - Màu sắc Đen Không Gian (Space Black) chống bám vân tay</li><li><strong>Hệ điều hành:</strong> macOS Sequoia</li></ul>', '2026-09-16 11:20:00');
