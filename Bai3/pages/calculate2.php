<h3 style="text-align: center; margin-bottom: 20px;">Trang Calculate 2</h3>

<?php
$hoten    = $_POST['hoten'] ?? '';
$lop      = $_POST['lop'] ?? '';
$diem1    = $_POST['diem1'] ?? '';
$diem2    = $_POST['diem2'] ?? '';
$diem3    = $_POST['diem3'] ?? '';
$tongdiem = '';
$msg      = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (trim($hoten) == '' || trim($lop) == '' || $diem1 === '' || $diem2 === '' || $diem3 === '') {
        $msg = 'Hãy nhập đủ thông tin!';
    } else if (!is_numeric($diem1) || !is_numeric($diem2) || !is_numeric($diem3)) {
        $msg = 'Điểm phải là số!';
    } else if ($diem1 > 10 || $diem1 < 0 || $diem2 > 10 || $diem2 < 0 || $diem3 > 10 || $diem3 < 0) {
        $msg = 'Điểm phải từ 0 đến 10!';
    } else {
        $tongdiem = floatval($diem1) + floatval($diem2) + floatval($diem3);
    }
}
?>

<form action="" method="POST" class="calc2-form">

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if ($msg !== ''): ?>
        <p class="error-msg"><?= $msg ?></p>
    <?php endif; ?>

    <div class="form-group">
        <label for="hoten">Họ Tên:</label>
        <input id="hoten" type="text" name="hoten" placeholder="Nhập họ tên..." value="<?= htmlspecialchars($hoten) ?>" required>
    </div>

    <div class="form-group">
        <label for="lop">Lớp:</label>
        <input id="lop" type="text" name="lop" placeholder="Nhập lớp..." value="<?= htmlspecialchars($lop) ?>" required>
    </div>

    <div class="form-group">
        <label for="diem1">Điểm môn 1:</label>
        <input id="diem1" type="number" name="diem1" step="any" min="0" max="10" placeholder="Nhập điểm môn 1..." value="<?= htmlspecialchars($diem1) ?>" required>
    </div>

    <div class="form-group">
        <label for="diem2">Điểm môn 2:</label>
        <input id="diem2" type="number" name="diem2" step="any" min="0" max="10" placeholder="Nhập điểm môn 2..." value="<?= htmlspecialchars($diem2) ?>" required>
    </div>

    <div class="form-group">
        <label for="diem3">Điểm môn 3:</label>
        <input id="diem3" type="number" name="diem3" step="any" min="0" max="10" placeholder="Nhập điểm môn 3..." value="<?= htmlspecialchars($diem3) ?>" required>
    </div>

    <div class="form-group">
        <label for="tong">Tổng điểm:</label>
        <input id="tong" type="text" name="tongdiem" readonly class="readonly-input" placeholder="Chưa có kết quả..." value="<?= ($tongdiem !== '') ? $tongdiem : "" ?>">
    </div>

    <div class="form-buttons">
        <button type="submit" class="btn-submit">Submit</button>
        <button type="button" class="btn-reset" onclick="window.location.href='index.php?page=calculate2'">Cancel</button>
    </div>

</form>