<?php
include 'Header.php';

// 1. Khai báo các hàm tính toán
function gt($a)
{
    if ($a <= 1) return 1;
    return $a * gt($a - 1);
}

function dientich($r)
{
    return pi() * $r * $r;
}

function thetich($r)
{
    return (4 / 3) * pi() * pow($r, 3);
}

// 2. Khởi tạo các biến
$so_n = '';
$kq_giaithua = '';

$ban_kinh = '';
$kq_dientich = '';
$kq_thetich = '';

// 3. Xử lý tính toán khi người dùng submit
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    // Nếu bấm nút tính Giai thừa
    if (isset($_POST['btn_giaithua']) && $_POST['so_n'] !== '') {
        $so_n = (int)$_POST['so_n'];
        $kq_giaithua = gt($so_n);
    }

    // Nếu bấm nút tính Diện tích & Thể tích hình tròn/cầu
    if (isset($_POST['btn_hinhtron']) && $_POST['ban_kinh'] !== '') {
        $ban_kinh = (float)$_POST['ban_kinh'];
        $kq_dientich = round(dientich($ban_kinh), 2);
        $kq_thetich  = round(thetich($ban_kinh), 2);
    }
}
?>

<!-- Tiêu đề trang -->
<h3 style="text-align: center; margin-bottom: 20px;">Trang tính toán</h3>

<!-- Form tính toán với ô kết quả kế bên -->
<form action="Caculate.php" method="POST" class="caculate-form">

    <!-- Hàng 1: Tính giai thừa -->
    <div class="form-row">
        <label for="so_n">Giai thừa (n):</label>
        <input type="number" id="so_n" name="so_n" min="0" step="1" placeholder="Nhập n..." value="<?= htmlspecialchars($so_n) ?>">
        <button type="submit" name="btn_giaithua">Tính</button>
        <input type="text" readonly placeholder="Kết quả giai thừa" value="<?= ($kq_giaithua !== '') ? "Kết quả: " . number_format($kq_giaithua) : '' ?>" class="result-input">
    </div>

    <!-- Hàng 2: Tính diện tích hình tròn & thể tích khối cầu -->
    <div class="form-row">
        <label for="ban_kinh">Bán kính (r):</label>
        <input type="number" id="ban_kinh" name="ban_kinh" min="0" step="any" placeholder="Nhập r..." value="<?= htmlspecialchars($ban_kinh) ?>">
        <button type="submit" name="btn_hinhtron">Tính</button>
        <input type="text" readonly placeholder="Kết quả S & V" value="<?= ($kq_dientich !== '') ? "S = $kq_dientich | V = $kq_thetich" : '' ?>" class="result-input">
    </div>

</form>

<!-- Dòng chữ "Hello" chuyển động theo đề bài -->
<hr style="max-width: 620px; margin: 25px auto 15px auto; border: 0; border-top: 1px solid #e0e0e0;">
<div style="max-width: 620px; margin: 0 auto;">
    <marquee direction="left" scrollamount="5" style="font-size: 20px; color: red; font-weight: bold;">
        Hello
    </marquee>
</div>

<?php include 'Footer.php'; ?>