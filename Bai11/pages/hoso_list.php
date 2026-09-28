<?php
// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../connect.php';

// 2. Cấu hình phân trang (10 bản ghi / trang)
$limit = 10;
$current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($current_page < 1) $current_page = 1;

$start = ($current_page - 1) * $limit;

// 3. Đếm tổng số học sinh để tính tổng số trang
$sql_count = "SELECT COUNT(MAHS) AS total FROM HOSO";
$res_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($res_count);
$total_records = (int)$row_count['total'];
$total_pages = ceil($total_records / $limit);

// 4. Hàm truy vấn và kết xuất dữ liệu học sinh theo phân trang
function renderHoSoTable($conn, $start, $limit)
{
    $sql = "SELECT * FROM HOSO ORDER BY MAHS ASC LIMIT {$start}, {$limit}";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $maHs     = htmlspecialchars($row['MAHS']);
            $hoTen    = htmlspecialchars($row['HOTEN']);
            $ngaySinh = htmlspecialchars($row['NGAYSINH']);
            $lop      = htmlspecialchars($row['LOP']);
            $diem     = "{$row['DIEMTOAN']} - {$row['DIEMLY']} - {$row['DIEMHOA']}";

            echo "
            <tr>
                <td class='text-center'><strong>{$maHs}</strong></td>
                <td>{$hoTen}</td>
                <td class='text-center'>{$ngaySinh}</td>
                <td class='text-center'><span class='badge badge-lop'>{$lop}</span></td>
                <td class='text-center'>{$diem}</td>
                <td class='text-center'>
                    <div class='action-buttons'>
                        <a href='index.php?page=hoso_form&id={$maHs}' class='btn btn-sm btn-warning'>Sửa</a>
                        <a href='index.php?page=hoso_delete&id={$maHs}' class='btn btn-sm btn-danger' onclick=\"return confirm('Bạn có chắc chắn muốn xóa học sinh {$maHs}?');\">Xóa</a>
                    </div>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>Chưa có dữ liệu học sinh nào!</td></tr>";
    }
}
?>

<!-- GIAO DIỆN: DANH SÁCH HỒ SƠ HỌC SINH (pages/hoso_list.php) -->
<div class="page-header">
    <h2 class="page-title">DANH SÁCH HỒ SƠ HỌC SINH</h2>
    <div class="action-bar">
        <a href="index.php?page=hoso_form" class="btn btn-success">+ Thêm Học Sinh Mới</a>
    </div>
</div>

<!-- Khung bảng danh sách học sinh -->
<div class="table-responsive">
    <table class="custom-table">
        <thead>
            <tr>
                <th class="text-center" width="90">Mã HS</th>
                <th>Họ Tên</th>
                <th class="text-center" width="120">Ngày Sinh</th>
                <th class="text-center" width="90">Lớp</th>
                <th class="text-center" width="140">Điểm (T/L/H)</th>
                <th class="text-center" width="160">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <!-- Gọi hàm render dữ liệu theo phân trang LIMIT -->
            <?php renderHoSoTable($conn, $start, $limit); ?>
        </tbody>
    </table>
</div>

<!-- Khung phân trang chuẩn theo đề bài -->
<div class="pagination-container">
    <div class="pagination-info">
        Hiển thị trang <strong><?= $current_page ?></strong> / <strong><?= $total_pages ?></strong> (Tổng số <?= $total_records ?> học sinh)
    </div>
    <ul class="pagination-list">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="<?= ($current_page == $i) ? 'active' : '' ?>">
                <?php if ($current_page == $i): ?>
                    <span>[<?= $i ?>]</span>
                <?php else: ?>
                    <a href="index.php?page=hoso_list&p=<?= $i ?>">[<?= $i ?>]</a>
                <?php endif; ?>
            </li>
        <?php endfor; ?>
    </ul>
</div>