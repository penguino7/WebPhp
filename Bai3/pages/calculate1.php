<h3 style="text-align: center; margin-bottom: 20px;">Trang Calculate 1</h3>

<?php
$a = $_POST['a'] ?? '';
$b = $_POST['b'] ?? '';
$pheptinh = $_POST['pheptinh'] ?? '';
$result = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $num_a = (int)$a;
    $num_b = (int)$b;
    switch ($pheptinh) {
        case '+':
            $result = "$num_a + $num_b = " . ($num_a + $num_b);
            break;

        case '-':
            $result = "$num_a - $num_b = " . ($num_a - $num_b);
            break;

        case '*':
            $result = "$num_a × $num_b = " . ($num_a * $num_b);
            break;

        case '/':
            if ($num_b != 0) {
                $result = "$num_a ÷ $num_b = " . round($num_a / $num_b, 2);
            } else {
                $result = "<span style='color: red;'>Không thể chia cho 0!</span>";
            }
            break;

        default:
            $result = "<span style='color: red;'>Vui lòng chọn phép tính!</span>";
    }
}
?>

<form action="" method="POST" class="calc1-form">
    <div class="form-group">
        <label for="so_a">Số a:</label>
        <input id="so_a" type="number" name="a" placeholder="Nhập số a..." value="<?= htmlspecialchars($a) ?>" required>
    </div>

    <div class="form-group">
        <label for="so_b">Số b:</label>
        <input id="so_b" type="number" name="b" placeholder="Nhập số b..." value="<?= htmlspecialchars($b) ?>" required>
    </div>

    <div class="form-group">
        <label>Phép tính:</label>
        <div class="radio-group">
            <label class="radio-label">
                <input type="radio" id="cong" name="pheptinh" value="+" <?= ($pheptinh == '+') ? 'checked' : '' ?> required>
                <span>+</span>
            </label>
            <label class="radio-label">
                <input type="radio" id="tru" name="pheptinh" value="-" <?= ($pheptinh == '-') ? 'checked' : '' ?>>
                <span>-</span>
            </label>
            <label class="radio-label">
                <input type="radio" id="nhan" name="pheptinh" value="*" <?= ($pheptinh == '*') ? 'checked' : '' ?>>
                <span>×</span>
            </label>
            <label class="radio-label">
                <input type="radio" id="chia" name="pheptinh" value="/" <?= ($pheptinh == '/') ? 'checked' : '' ?>>
                <span>÷</span>
            </label>
        </div>
    </div>

    <div class="form-buttons">
        <button type="submit" class="btn-submit">Calculate</button>
    </div>
</form>

<?php if ($result !== ''): ?>
    <div class="calc-result-box">
        <strong>Kết quả:</strong> <span><?= $result ?></span>
    </div>
<?php endif; ?>