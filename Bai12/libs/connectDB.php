<?php
// ==========================================================================
// BÀI 12 - THƯ VIỆN MỞ KẾT NỐI CSDL (libs/connectDB.php)
// ==========================================================================

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "quanlysinhvien_b12";

// Mở kết nối tới MySQL Server
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối CSDL thất bại: " . mysqli_connect_error());
}

// Thiết lập bảng mã tiếng Việt chuẩn UTF-8
mysqli_set_charset($conn, "utf8mb4");
?>
