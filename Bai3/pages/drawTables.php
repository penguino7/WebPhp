<h3 style="text-align: center; margin-bottom: 20px;">Trang DrawTable</h3>

<!-- Form nhập số dòng và số cột -->
<form action="" method="POST" class="draw-table-form">
    <p class="form-title"><b>Form vẽ bảng:</b></p>

    <div class="form-group">
        <label for="sodong">Số dòng:</label>
        <input type="number" id="sodong" name="sodong" min="1" placeholder="Nhập số dòng..." value="<?= htmlspecialchars($_POST['sodong'] ?? "") ?>" required>
    </div>

    <div class="form-group">
        <label for="socot">Số cột:</label>
        <input type="number" id="socot" name="socot" min="1" placeholder="Nhập số cột..." value="<?= htmlspecialchars($_POST['socot'] ?? "") ?>" required>
    </div>

    <div class="form-buttons">
        <button type="reset" class="btn-reset" onclick="window.location.href='index.php?page=drawTable'">Nhập lại</button>
        <button type="submit" name="btnVe" class="btn-submit">Vẽ</button>
    </div>

    <p class="form-note"><i>(Khi Click nút Vẽ thì mới vẽ bảng và hiển thị bên dưới)</i></p>
</form>

<?php
if (isset($_POST['btnVe'])) {
    $dong = (int)($_POST['sodong'] ?? 0);
    $cot  = (int)($_POST['socot'] ?? 0);

    if ($dong > 0 && $cot > 0) {
        echo "<div class='table-result-wrapper'>";
        echo "<h4>Kết quả:</h4>";
        echo "<table class='custom-table'>";

        for ($i = 1; $i <= $dong; $i++) {
            echo "<tr>";
            for ($j = 1; $j <= $cot; $j++) {
                // Nếu cột j <= dòng i thì in số j, ngược lại để ô trống
                if ($j <= $i) {
                    echo "<td>$j</td>";
                } else {
                    echo "<td></td>";
                }
            }
            echo "</tr>"; // Đóng thẻ tr
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "<p class='error-msg'>Vui lòng nhập số dòng và số cột lớn hơn 0!</p>";
    }
}
?>