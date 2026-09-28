<?php
// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../connect.php';

// 2. Hàm truy vấn và kết xuất bảng dữ liệu lớp học
function renderLopTable($conn)
{
    $sql = "SELECT * FROM LOP ORDER BY MALOP ASC";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $stt = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $maLop   = htmlspecialchars($row['MALOP']);
            $tenLop  = htmlspecialchars($row['TENLOP']);
            $khoaHoc = htmlspecialchars($row['KHOAHOC']);
            $gvcn    = htmlspecialchars($row['GVCN']);

            echo "
            <tr>
                <td class='text-center'>" . ($stt++) . "</td>
                <td><strong>{$maLop}</strong></td>
                <td>{$tenLop}</td>
                <td class='text-center'>{$khoaHoc}</td>
                <td>{$gvcn}</td>
                <td class='text-center'>
                    <div class='action-buttons'>
                        <a href='index.php?page=lop_form&id={$maLop}' class='btn btn-sm btn-warning'>Sửa</a>
                        <a href='index.php?page=lop_delete&id={$maLop}' class='btn btn-sm btn-danger' onclick=\"return confirm('Bạn có chắc chắn muốn xóa lớp {$maLop}?');\">Xóa</a>
                    </div>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>Chưa có dữ liệu lớp học nào!</td></tr>";
    }
}
?>

<div class="page-header">
    <h2 class="page-title">DANH SÁCH LỚP HỌC</h2>
    <div class="action-bar">
        <a href="index.php?page=lop_form" class="btn btn-primary">+ Thêm Lớp Mới</a>
    </div>
</div>

<!-- Khung bảng danh sách lớp học -->
<div class="table-responsive">
    <table class="custom-table">
        <thead>
            <tr>
                <th class="text-center" width="80">STT</th>
                <th width="120">Mã Lớp</th>
                <th>Tên Lớp</th>
                <th class="text-center" width="120">Khóa Học</th>
                <th>GVCN</th>
                <th class="text-center" width="160">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <!-- Gọi hàm render dữ liệu từ MySQL -->
            <?php renderLopTable($conn); ?>
        </tbody>
    </table>
</div>