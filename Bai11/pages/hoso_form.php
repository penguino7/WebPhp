<?php
// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../connect.php';

// 2. Khởi tạo các biến mặc định cho Form
$is_edit  = false;
$id       = "";
$hoten    = "";
$ngaysinh = "";
$diachi   = "";
$malop    = "";
$dtoan    = 0;
$dly      = 0;
$dhoa     = 0;

// 3. KIỂM TRA CHẾ ĐỘ SỬA: Nếu có ?id= trên URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $is_edit = true;
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Lấy dữ liệu cũ của học sinh này từ MySQL
    $sql = "SELECT * FROM HOSO WHERE MAHS = '{$id}'";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $hoten    = $row['HOTEN'];
        $ngaysinh = $row['NGAYSINH'];
        $diachi   = $row['DIACHI'];
        $malop    = $row['LOP'];
        $dtoan    = $row['DIEMTOAN'];
        $dly      = $row['DIEMLY'];
        $dhoa     = $row['DIEMHOA'];
    }
}

// 4. XỬ LÝ KHI NGƯỜI DÙNG BẤM NÚT LƯU HỒ SƠ
if (isset($_POST['btnLuu'])) {
    $hoTen_new    = mysqli_real_escape_string($conn, trim($_POST['hoten']));
    $lop_new      = mysqli_real_escape_string($conn, trim($_POST['lop']));
    $ngaySinh_new = !empty($_POST['ngaysinh']) ? "'" . mysqli_real_escape_string($conn, $_POST['ngaysinh']) . "'" : "NULL";
    $diaChi_new   = mysqli_real_escape_string($conn, trim($_POST['diachi']));
    $dToan_new    = (float)$_POST['dtoan'];
    $dLy_new      = (float)$_POST['dly'];
    $dHoa_new     = (float)$_POST['dhoa'];

    if ($is_edit) {
        // Chế độ SỬA -> UPDATE
        $sql = "UPDATE HOSO 
                SET HOTEN = '{$hoTen_new}', 
                    LOP = '{$lop_new}', 
                    NGAYSINH = {$ngaySinh_new}, 
                    DIACHI = '{$diaChi_new}', 
                    DIEMTOAN = {$dToan_new}, 
                    DIEMLY = {$dLy_new}, 
                    DIEMHOA = {$dHoa_new} 
                WHERE MAHS = '{$id}'";
        $msg = "Cập nhật hồ sơ học sinh thành công!";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('{$msg}'); window.location.href='index.php?page=hoso_list';</script>";
            exit;
        } else {
            echo "<script>alert('Lỗi cập nhật: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        // Chế độ THÊM MỚI -> INSERT
        $maHs_new = mysqli_real_escape_string($conn, trim($_POST['mahs']));

        // Kiểm tra xem mã học sinh đã tồn tại chưa
        $check_sql = "SELECT MAHS FROM HOSO WHERE MAHS = '{$maHs_new}'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            echo "<script>alert('Lỗi: Mã học sinh {$maHs_new} đã tồn tại trong hệ thống!');</script>";
        } else {
            $sql = "INSERT INTO HOSO (MAHS, HOTEN, NGAYSINH, DIACHI, LOP, DIEMTOAN, DIEMLY, DIEMHOA) 
                    VALUES ('{$maHs_new}', '{$hoTen_new}', {$ngaySinh_new}, '{$diaChi_new}', '{$lop_new}', {$dToan_new}, {$dLy_new}, {$dHoa_new})";
            $msg = "Thêm học sinh mới thành công!";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('{$msg}'); window.location.href='index.php?page=hoso_list';</script>";
                exit;
            } else {
                echo "<script>alert('Lỗi thêm mới: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}

// 5. Truy vấn danh sách lớp học để đổ vào thẻ <select>
$sql_lop = "SELECT MALOP, TENLOP FROM LOP ORDER BY MALOP ASC";
$res_lop = mysqli_query($conn, $sql_lop);
?>

<!-- GIAO DIỆN: FORM THÊM / SỬA HỒ SƠ HỌC SINH (pages/hoso_form.php) -->
<div class="page-header">
    <h2 class="page-title"><?= $is_edit ? "SỬA HỒ SƠ HỌC SINH" : "THÊM HỒ SƠ HỌC SINH MỚI" ?></h2>
    <div class="action-bar">
        <a href="index.php?page=hoso_list" class="btn btn-secondary">&larr; Quay Lại Danh Sách</a>
    </div>
</div>

<div class="form-card">
    <div class="form-title"><?= $is_edit ? "Cập Nhật Hồ Sơ: <strong>{$id}</strong>" : "Nhập Thông Tin Học Sinh Mới" ?></div>

    <form action="index.php?page=hoso_form<?= $is_edit ? '&id=' . urlencode($id) : '' ?>" method="POST">
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
                    <option value="">-- Chọn lớp học --</option>
                    <?php
                    if ($res_lop && mysqli_num_rows($res_lop) > 0) {
                        while ($r_lop = mysqli_fetch_assoc($res_lop)) {
                            $selected = ($malop === $r_lop['MALOP']) ? 'selected' : '';
                            echo "<option value='{$r_lop['MALOP']}' {$selected}>{$r_lop['MALOP']} - {$r_lop['TENLOP']}</option>";
                        }
                    }
                    ?>
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
                    value="<?= htmlspecialchars($dtoan) ?>" placeholder="0 - 10" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="dly">Điểm Lý</label>
                <input type="number" step="0.1" min="0" max="10" id="dly" name="dly" class="form-control"
                    value="<?= htmlspecialchars($dly) ?>" placeholder="0 - 10" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="dhoa">Điểm Hóa</label>
                <input type="number" step="0.1" min="0" max="10" id="dhoa" name="dhoa" class="form-control"
                    value="<?= htmlspecialchars($dhoa) ?>" placeholder="0 - 10" required>
            </div>
        </div>

        <!-- 5. Nút Thao Tác -->
        <div class="form-actions">
            <a href="index.php?page=hoso_list" class="btn btn-secondary">Hủy Bỏ</a>
            <input type="submit" name="btnLuu" value="<?= $is_edit ? 'Cập Nhật Hồ Sơ' : 'Lưu Dữ Liệu' ?>" class="btn btn-success">
        </div>
    </form>
</div>