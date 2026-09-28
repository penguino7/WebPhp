<?php
// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../connect.php';

// 2. Khởi tạo các biến mặc định cho Form
$is_edit = false;
$id      = "";
$tenlop  = "";
$khoahoc = "";
$gvcn    = "";

// 3. KIỂM TRA CHẾ ĐỘ SỬA: Nếu có ?id= trên URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $is_edit = true;
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Lấy dữ liệu cũ của lớp này từ MySQL
    $sql = "SELECT * FROM LOP WHERE MALOP = '{$id}'";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $tenlop  = $row['TENLOP'];
        $khoahoc = $row['KHOAHOC'];
        $gvcn    = $row['GVCN'];
    }
}

// 4. XỬ LÝ KHI NGƯỜI DÙNG BẤM NÚT LƯU
if (isset($_POST['btnLuu'])) {
    $tenLop_new  = mysqli_real_escape_string($conn, trim($_POST['tenlop']));
    $khoaHoc_new = !empty($_POST['khoahoc']) ? (int)$_POST['khoahoc'] : "NULL";
    $gvcn_new    = mysqli_real_escape_string($conn, trim($_POST['gvcn']));

    if ($is_edit) {
        // Chế độ SỬA -> UPDATE
        $sql = "UPDATE LOP 
                SET TENLOP = '{$tenLop_new}', KHOAHOC = {$khoaHoc_new}, GVCN = '{$gvcn_new}' 
                WHERE MALOP = '{$id}'";
        $msg = "Cập nhật lớp học thành công!";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('{$msg}'); window.location.href='index.php?page=lop_list';</script>";
            exit;
        } else {
            echo "<script>alert('Lỗi cập nhật: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        // Chế độ THÊM MỚI -> INSERT
        $maLop_new = mysqli_real_escape_string($conn, trim($_POST['malop']));

        // Kiểm tra xem mã lớp đã tồn tại chưa
        $check_sql = "SELECT MALOP FROM LOP WHERE MALOP = '{$maLop_new}'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            echo "<script>alert('Lỗi: Mã lớp {$maLop_new} đã tồn tại trong hệ thống!');</script>";
        } else {
            $sql = "INSERT INTO LOP (MALOP, TENLOP, KHOAHOC, GVCN) 
                    VALUES ('{$maLop_new}', '{$tenLop_new}', {$khoaHoc_new}, '{$gvcn_new}')";
            $msg = "Thêm lớp học mới thành công!";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('{$msg}'); window.location.href='index.php?page=lop_list';</script>";
                exit;
            } else {
                echo "<script>alert('Lỗi thêm mới: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}
?>

<!-- GIAO DIỆN: FORM THÊM / SỬA LỚP HỌC (pages/lop_form.php) -->
<div class="page-header">
    <h2 class="page-title"><?= $is_edit ? "SỬA LỚP HỌC" : "THÊM LỚP HỌC MỚI" ?></h2>
    <div class="action-bar">
        <a href="index.php?page=lop_list" class="btn btn-secondary">&larr; Quay Lại Danh Sách</a>
    </div>
</div>

<div class="form-card">
    <div class="form-title"><?= $is_edit ? "Cập Nhật Thông Tin Lớp: <strong>{$id}</strong>" : "Nhập Thông Tin Lớp Học Mới" ?></div>

    <form action="index.php?page=lop_form<?= $is_edit ? '&id=' . urlencode($id) : '' ?>" method="POST">
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
            <input type="submit" name="btnLuu" value="<?= $is_edit ? 'Cập Nhật Lớp' : 'Thêm Lớp Mới' ?>" class="btn btn-primary">
        </div>
    </form>
</div>