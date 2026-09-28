<?php
// ==========================================================================
// BÀI 11: FILE XỬ LÝ XÓA LỚP HỌC (pages/lop_delete.php)
// ==========================================================================

// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../connect.php';

// 2. Kiểm tra xem có mã lớp truyền vào qua GET hay không
if (isset($_GET['id']) && !empty($_GET['id'])) {

    // 3. Làm sạch dữ liệu chống SQL Injection
    $maLop = mysqli_real_escape_string($conn, $_GET['id']);

    // 4. Thực thi câu lệnh xóa trong MySQL
    $sql = "DELETE FROM LOP WHERE MALOP = '{$maLop}'";

    if (mysqli_query($conn, $sql)) {
        // Xóa thành công
        echo "<script>
            alert('Xóa lớp học {$maLop} thành công!');
            window.location.href = 'index.php?page=lop_list';
        </script>";
        exit;
    } else {
        // Xóa thất bại do vi phạm ràng buộc khóa ngoại (lớp đang có học sinh)
        echo "<script>
            alert('Lỗi: Không thể xóa lớp {$maLop} vì đang có học sinh theo học!');
            window.location.href = 'index.php?page=lop_list';
        </script>";
        exit;
    }
} else {
    // Nếu không có ID hợp lệ -> Quay về trang danh sách
    header("Location: index.php?page=lop_list");
    exit;
}
?>
