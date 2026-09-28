-- =======================================================
-- KỊCH BẢN TẠO CƠ SỞ DỮ LIỆU & DỮ LIỆU MẪU CHO BÀI 11
-- Database: quanlyhocsinh
-- =======================================================

-- 1. Tạo Cơ Sở Dữ Liệu nếu chưa tồn tại
CREATE DATABASE IF NOT EXISTS quanlyhocsinh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quanlyhocsinh;

-- 2. Xóa các bảng cũ nếu đã tồn tại (Xóa bảng con HOSO trước, bảng cha LOP sau)
DROP TABLE IF EXISTS HOSO;
DROP TABLE IF EXISTS LOP;

-- 3. Tạo bảng LOP (Bảng Cha)
CREATE TABLE LOP (
    MALOP CHAR(6) PRIMARY KEY,
    TENLOP VARCHAR(50) NOT NULL,
    KHOAHOC INT,
    GVCN VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tạo bảng HOSO (Bảng Con có khóa ngoại tham chiếu LOP)
CREATE TABLE HOSO (
    MAHS CHAR(8) PRIMARY KEY,
    HOTEN VARCHAR(50) NOT NULL,
    NGAYSINH DATE,
    DIACHI VARCHAR(150),
    LOP CHAR(6),
    DIEMTOAN FLOAT DEFAULT 0,
    DIEMLY FLOAT DEFAULT 0,
    DIEMHOA FLOAT DEFAULT 0,
    CONSTRAINT fk_hoso_lop FOREIGN KEY (LOP) REFERENCES LOP(MALOP) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Nạp dữ liệu mẫu cho bảng LOP
INSERT INTO LOP (MALOP, TENLOP, KHOAHOC, GVCN) VALUES
('CNTT1', 'Công nghệ thông tin 1', 15, 'Thầy Nguyễn Văn An'),
('CNTT2', 'Công nghệ thông tin 2', 15, 'Cô Trần Thị Bích'),
('KT01',  'Kế toán doanh nghiệp 1', 16, 'Thầy Lê Hoàng Nam'),
('QTKD1', 'Quản trị kinh doanh 1', 16, 'Cô Phạm Hồng Nhung');

-- 6. Nạp dữ liệu mẫu phong phú cho bảng HOSO (16 học sinh để test phân trang 10 dòng/trang)
INSERT INTO HOSO (MAHS, HOTEN, NGAYSINH, DIACHI, LOP, DIEMTOAN, DIEMLY, DIEMHOA) VALUES
('HS0001', 'Nguyễn Văn Minh',     '2004-05-12', 'Hà Nội',      'CNTT1', 8.5, 9.0, 8.0),
('HS0002', 'Trần Thị Mai',        '2004-08-20', 'Đà Nẵng',     'CNTT1', 7.0, 6.5, 7.5),
('HS0003', 'Lê Quốc Bảo',         '2004-01-15', 'Hải Phòng',   'CNTT1', 9.0, 9.5, 9.0),
('HS0004', 'Phạm Quỳnh Nga',      '2004-11-03', 'Nam Định',    'CNTT2', 6.0, 7.0, 6.5),
('HS0005', 'Hoàng Gia Huy',       '2004-03-25', 'Hà Nội',      'CNTT2', 5.5, 6.0, 5.0),
('HS0006', 'Vũ Thị Hương',        '2004-09-18', 'Thái Bình',   'CNTT2', 8.0, 8.5, 8.0),
('HS0007', 'Đỗ Thành Đạt',        '2005-02-10', 'Bắc Ninh',    'KT01',  9.5, 8.5, 9.0),
('HS0008', 'Bùi Kim Ngân',        '2005-07-30', 'Hà Nam',      'KT01',  4.5, 5.0, 4.0),
('HS0009', 'Ngô Hoàng Nam',       '2005-12-05', 'Ninh Bình',   'KT01',  7.5, 7.0, 8.0),
('HS0010', 'Đặng Thùy Dung',      '2005-06-14', 'Hà Nội',      'QTKD1', 8.0, 7.5, 8.5),
('HS0011', 'Trịnh Bá Hưng',       '2005-04-22', 'Quảng Ninh',  'QTKD1', 6.5, 6.0, 7.0),
('HS0012', 'Cao Thu Trang',       '2005-10-09', 'Hà Nội',      'QTKD1', 9.0, 9.0, 8.5),
('HS0013', 'Dương Đức Anh',       '2004-07-17', 'Vĩnh Phúc',   'CNTT1', 7.5, 8.0, 7.0),
('HS0014', 'Mai Khánh Linh',      '2004-04-01', 'Thanh Hóa',   'CNTT2', 8.5, 9.0, 9.5),
('HS0015', 'Phan Tuấn Kiệt',      '2005-08-19', 'Nghệ An',     'KT01',  6.0, 6.5, 6.0),
('HS0016', 'Lâm Thảo Vy',         '2005-03-11', 'Hà Tĩnh',     'QTKD1', 8.5, 8.0, 8.5);
