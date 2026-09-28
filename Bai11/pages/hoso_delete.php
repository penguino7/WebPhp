<?php
// ==========================================================================
// BÀI 11: FILE XỬ LÝ XÓA HỒ SƠ HỌC SINH (pages/hoso_delete.php)
// Nhận tham số ?id=MAHS từ URL để xóa học sinh khỏi CSDL
// ==========================================================================

// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../connect.php';

// 2. Kiểm tra nếu có mã học sinh truyền vào qua GET
if (isset($_GET['id']) && !empty($_GET['id'])) {

    // 3. Làm sạch dữ liệu chống SQL Injection
    $maHs = mysqli_real_escape_string($conn, $_GET['id']);

    // 4. Thực thi câu lệnh xóa học sinh
    $sql = "DELETE FROM HOSO WHERE MAHS = '{$maHs}'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Xóa học sinh {$maHs} thành công!');
            window.location.href = 'index.php?page=hoso_list';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Lỗi xóa học sinh: " . mysqli_error($conn) . "');
            window.location.href = 'index.php?page=hoso_list';
        </script>";
        exit;
    }
} else {
    // Nếu không có ID hợp lệ -> Quay về trang danh sách
    header("Location: index.php?page=hoso_list");
    exit;
}
