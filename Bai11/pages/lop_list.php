<!-- GIAO DIỆN: DANH SÁCH LỚP HỌC (pages/lop_list.php) -->

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
            <!-- Giao diện mẫu (Chưa nhúng SQL) -->
            <tr>
                <td class="text-center">1</td>
                <td><strong>CNTT1</strong></td>
                <td>Công nghệ thông tin 1</td>
                <td class="text-center">15</td>
                <td>Thầy A</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <a href="index.php?page=lop_form&id=CNTT1" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="index.php?page=lop_list&action=delete&id=CNTT1" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?');">Xóa</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td><strong>KT1</strong></td>
                <td>Kế toán 1</td>
                <td class="text-center">15</td>
                <td>Cô B</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <a href="index.php?page=lop_form&id=KT1" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="index.php?page=lop_list&action=delete&id=KT1" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?');">Xóa</a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>