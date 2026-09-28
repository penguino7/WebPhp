<?php
// Biến khởi tạo cho Form Thêm/Sửa Lớp
$is_edit = false;
$id = "";
$tenlop = "";
$khoahoc = "";
$gvcn = "";
?>

<!-- GIAO DIỆN: FORM THÊM / SỬA LỚP HỌC (pages/lop_form.php) -->
<div class="page-header">
    <h2 class="page-title"><?= $is_edit ? "SỬA LỚP" : "THÊM LỚP MỚI" ?></h2>
    <div class="action-bar">
        <a href="index.php?page=lop_list" class="btn btn-secondary">&larr; Quay Lại Danh Sách</a>
    </div>
</div>

<div class="form-card">
    <div class="form-title">Nhập Thông Tin Lớp Học</div>

    <form action="index.php?page=lop_form" method="POST">
        <!-- 1. Mã Lớp -->
        <div class="form-group">
            <label class="form-label" for="malop">Mã Lớp <span class="required">*</span></label>
            <input type="text" id="malop" name="malop" class="form-control"
                value="<?= htmlspecialchars($id) ?>"
                <?= $is_edit ? 'readonly' : '' ?>
                placeholder="Ví dụ: CNTT1, KT1..." maxlength="6" required>
        </div>

        <!-- 2. Tên Lớp -->
        <div class="form-group">
            <label class="form-label" for="tenlop">Tên Lớp <span class="required">*</span></label>
            <input type="text" id="tenlop" name="tenlop" class="form-control"
                value="<?= htmlspecialchars($tenlop) ?>"
                placeholder="Ví dụ: Công nghệ thông tin 1" maxlength="50" required>
        </div>

        <!-- 3. Khóa Học & GVCN -->
        <div class="form-group-row">
            <div class="form-group">
                <label class="form-label" for="khoahoc">Khóa học</label>
                <input type="number" id="khoahoc" name="khoahoc" class="form-control"
                    value="<?= htmlspecialchars($khoahoc) ?>"
                    placeholder="Ví dụ: 15, 16...">
            </div>
            <div class="form-group">
                <label class="form-label" for="gvcn">GVCN</label>
                <input type="text" id="gvcn" name="gvcn" class="form-control"
                    value="<?= htmlspecialchars($gvcn) ?>"
                    placeholder="Ví dụ: Thầy A, Cô B..." maxlength="50">
            </div>
        </div>

        <!-- 4. Nút Thao Tác -->
        <div class="form-actions">
            <a href="index.php?page=lop_list" class="btn btn-secondary">Hủy Bỏ</a>
            <input type="submit" name="btnLuu" value="Lưu Dữ Liệu" class="btn btn-primary">
        </div>
    </form>
</div>