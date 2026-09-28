<?php
// Biến khởi tạo cho Form Thêm/Sửa Học Sinh
$id = "";
$hoten = "";
$ngaysinh = "";
$diachi = "";
$malop = "";
$dtoan = "";
$dly = "";
$dhoa = "";
$is_edit = false;
?>

<!-- GIAO DIỆN: FORM THÊM / SỬA HỒ SƠ HỌC SINH (pages/hoso_form.php) -->
<div class="page-header">
    <h2 class="page-title"><?= $is_edit ? "SỬA HỒ SƠ HỌC SINH" : "THÊM HỒ SƠ HỌC SINH MỚI" ?></h2>
    <div class="action-bar">
        <a href="index.php?page=hoso_list" class="btn btn-secondary">&larr; Quay Lại Danh Sách</a>
    </div>
</div>

<div class="form-card">
    <div class="form-title">Nhập Thông Tin Học Sinh</div>

    <form action="index.php?page=hoso_form" method="POST">
        <!-- 1. Mã HS & Lớp -->
        <div class="form-group-row">
            <div class="form-group">
                <label class="form-label" for="mahs">Mã HS <span class="required">*</span></label>
                <input type="text" id="mahs" name="mahs" class="form-control"
                    value="<?= htmlspecialchars($id) ?>"
                    <?= $is_edit ? 'readonly' : '' ?>
                    placeholder="Ví dụ: HS01" maxlength="8" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="lop">Lớp <span class="required">*</span></label>
                <select id="lop" name="lop" class="form-control" required>
                    <option value="">-- Chọn lớp --</option>
                    <option value="CNTT1" <?= ($malop === 'CNTT1') ? 'selected' : '' ?>>CNTT1 - Công nghệ thông tin 1</option>
                    <option value="KT1" <?= ($malop === 'KT1') ? 'selected' : '' ?>>KT1 - Kế toán 1</option>
                </select>
            </div>
        </div>

        <!-- 2. Họ Tên -->
        <div class="form-group">
            <label class="form-label" for="hoten">Họ Tên <span class="required">*</span></label>
            <input type="text" id="hoten" name="hoten" class="form-control"
                value="<?= htmlspecialchars($hoten) ?>"
                placeholder="Ví dụ: Nguyen Van A" maxlength="50" required>
        </div>

        <!-- 3. Ngày Sinh & Địa Chỉ -->
        <div class="form-group-row">
            <div class="form-group">
                <label class="form-label" for="ngaysinh">Ngày Sinh</label>
                <input type="date" id="ngaysinh" name="ngaysinh" class="form-control"
                    value="<?= htmlspecialchars($ngaysinh) ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="diachi">Địa Chỉ</label>
                <input type="text" id="diachi" name="diachi" class="form-control"
                    value="<?= htmlspecialchars($diachi) ?>"
                    placeholder="Ví dụ: Ha Noi" maxlength="150">
            </div>
        </div>

        <!-- 4. Điểm Toán, Lý, Hóa -->
        <div class="form-group-row">
            <div class="form-group">
                <label class="form-label" for="dtoan">Điểm Toán</label>
                <input type="number" step="0.1" min="0" max="10" id="dtoan" name="dtoan" class="form-control"
                    value="<?= htmlspecialchars($dtoan) ?>" placeholder="0 - 10">
            </div>
            <div class="form-group">
                <label class="form-label" for="dly">Điểm Lý</label>
                <input type="number" step="0.1" min="0" max="10" id="dly" name="dly" class="form-control"
                    value="<?= htmlspecialchars($dly) ?>" placeholder="0 - 10">
            </div>
            <div class="form-group">
                <label class="form-label" for="dhoa">Điểm Hóa</label>
                <input type="number" step="0.1" min="0" max="10" id="dhoa" name="dhoa" class="form-control"
                    value="<?= htmlspecialchars($dhoa) ?>" placeholder="0 - 10">
            </div>
        </div>

        <!-- 5. Nút Thao Tác -->
        <div class="form-actions">
            <a href="index.php?page=hoso_list" class="btn btn-secondary">Hủy Bỏ</a>
            <input type="submit" name="btnLuu" value="Lưu Dữ Liệu" class="btn btn-success">
        </div>
    </form>
</div>