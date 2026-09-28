<?php
// Biến giả lập phân trang giao diện mẫu
$page_num = isset($_GET['p']) ? (int)$_GET['p'] : (isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1);
$total_pages = 2;
?>

<!-- GIAO DIỆN: DANH SÁCH HỒ SƠ HỌC SINH (pages/hoso_list.php) -->
<div class="page-header">
    <h2 class="page-title">DANH SÁCH HỒ SƠ HỌC SINH</h2>
    <div class="action-bar">
        <a href="index.php?page=hoso_form" class="btn btn-success">+ Thêm Học Sinh</a>
    </div>
</div>

<!-- Khung bảng danh sách học sinh -->
<div class="table-responsive">
    <table class="custom-table">
        <thead>
            <tr>
                <th class="text-center" width="80">Mã HS</th>
                <th>Họ Tên</th>
                <th width="110">Ngày Sinh</th>
                <th class="text-center" width="90">Lớp</th>
                <th class="text-center" width="130">Điểm (T/L/H)</th>
                <th class="text-center" width="140">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dữ liệu mẫu giao diện (Chưa nhúng SQL) -->
            <tr>
                <td class="text-center"><strong>HS01</strong></td>
                <td>Nguyen Van A</td>
                <td>2000-01-01</td>
                <td class="text-center"><span class="badge badge-lop">CNTT1</span></td>
                <td class="text-center">8 - 7 - 9</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <a href="index.php?page=hoso_form&id=HS01" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="index.php?page=hoso_delete&id=HS01" class="btn btn-sm btn-danger" onclick="return confirm('Xóa?');">Xóa</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-center"><strong>HS02</strong></td>
                <td>Tran Thi B</td>
                <td>2000-02-02</td>
                <td class="text-center"><span class="badge badge-lop">CNTT1</span></td>
                <td class="text-center">9 - 8 - 8</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <a href="index.php?page=hoso_form&id=HS02" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="index.php?page=hoso_delete&id=HS02" class="btn btn-sm btn-danger" onclick="return confirm('Xóa?');">Xóa</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-center"><strong>HS03</strong></td>
                <td>Le Van C</td>
                <td>2000-03-03</td>
                <td class="text-center"><span class="badge badge-lop">KT1</span></td>
                <td class="text-center">7 - 6 - 5</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <a href="index.php?page=hoso_form&id=HS03" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="index.php?page=hoso_delete&id=HS03" class="btn btn-sm btn-danger" onclick="return confirm('Xóa?');">Xóa</a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Khung phân trang chuẩn theo đề bài -->
<div class="pagination-container">
    <div class="pagination-info">
        Trang:
    </div>
    <ul class="pagination-list">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="<?= ($page_num == $i) ? 'active' : '' ?>">
                <?php if ($page_num == $i): ?>
                    <span>[<?= $i ?>]</span>
                <?php else: ?>
                    <a href="index.php?page=hoso_list&p=<?= $i ?>">[<?= $i ?>]</a>
                <?php endif; ?>
            </li>
        <?php endfor; ?>
    </ul>
</div>