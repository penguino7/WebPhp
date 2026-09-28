-- =======================================================
-- KỊCH BẢN TẠO CƠ SỞ DỮ LIỆU & DỮ LIỆU MẪU CHO BÀI 12
-- Database: quanlysinhvien_b12
-- =======================================================

-- 1. Tạo Database
CREATE DATABASE IF NOT EXISTS quanlysinhvien_b12 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quanlysinhvien_b12;

-- 2. Xóa bảng cũ nếu đã tồn tại (Xóa bảng con students trước, bảng cha classes sau)
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS classes;

-- 3. Tạo bảng classes (Bảng Cha)
CREATE TABLE classes (
    ID VARCHAR(20) PRIMARY KEY,
    ClassName VARCHAR(50) NOT NULL,
    ClassDescription TEXT,
    NumOfStudents INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tạo bảng students (Bảng Con có khóa ngoại tham chiếu classes)
CREATE TABLE students (
    ID VARCHAR(20) PRIMARY KEY,
    StudentName VARCHAR(50) NOT NULL,
    StudentGender VARCHAR(10) DEFAULT 'Nam',
    StudentAddress VARCHAR(150),
    StudentImage VARCHAR(100),
    ClassID VARCHAR(20),
    CONSTRAINT fk_students_classes FOREIGN KEY (ClassID) REFERENCES classes(ID) 
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Nạp dữ liệu mẫu cho bảng classes (Theo đúng giáo trình)
INSERT INTO classes (ID, ClassName, ClassDescription, NumOfStudents) VALUES
('PHP001', 'Lập trình PHP chuyên sâu 01', 'Lớp học lập trình web backend PHP & MySQL', 35),
('0908M',  'Kỹ thuật phần mềm 0908M',      'Lớp kỹ sư phần mềm hệ đại học chính quy', 40),
('0908L',  'Hệ thống thông tin 0908L',     'Lớp phân tích và thiết kế hệ thống CSDL', 38),
('09A3G',  'Khoa học máy tính 09A3G',      'Lớp giải thuật và trí tuệ nhân tạo', 42),
('09A1H',  'An toàn thông tin 09A1H',      'Lớp an toàn và bảo mật mạng máy tính', 36);

-- 6. Nạp dữ liệu mẫu cho bảng students (Kèm liên kết tới các lớp)
INSERT INTO students (ID, StudentName, StudentGender, StudentAddress, StudentImage, ClassID) VALUES
('SV001', 'Nguyễn Trãi',     'Nam', 'Hải Dương', '1.jpg', 'PHP001'),
('SV002', 'Nguyễn Du',       'Nam', 'Huế',       '2.jpg', 'PHP001'),
('SV003', 'Hồ Xuân Hương',   'Nữ',  'Việt Nam',  '3.jpg', 'PHP001'),
('SV004', 'Trần Hưng Đạo',   'Nam', 'Nam Định',  '1.jpg', '0908M'),
('SV005', 'Lê Lợi',          'Nam', 'Thanh Hóa', '2.jpg', '0908M'),
('SV006', 'Nguyễn Huệ',      'Nam', 'Bình Định', '3.jpg', '0908M'),
('SV007', 'Chu Văn An',      'Nam', 'Hà Nội',    '1.jpg', '0908L'),
('SV008', 'Đoàn Thị Điểm',   'Nữ',  'Hưng Yên',  '2.jpg', '0908L'),
('SV009', 'Lê Quý Đôn',      'Nam', 'Thái Bình', '3.jpg', '09A3G'),
('SV010', 'Ngô Quyền',       'Nam', 'Hải Phòng', '1.jpg', '09A1H');
