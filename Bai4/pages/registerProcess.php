<h3 style="text-align: center; margin-bottom: 20px;">Kết quả Đăng ký</h3>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" || isset($_POST['btnRegister'])) {
    // 1. Lấy dữ liệu Textbox và Password
    $username = $_POST['txtUsername'] ?? '';
    $password = $_POST['txtPassword'] ?? '';

    // 2. Lấy dữ liệu RadioButton Gender
    $gender = $_POST['radGender'] ?? 'Chưa chọn';

    // 3. Lấy dữ liệu Select List Address
    $address = $_POST['lstAddress'] ?? 'Chưa chọn';

    // 4. Lấy dữ liệu CheckBox List Ngôn ngữ lập trình (Mảng)
    $chkLang = $_POST['chkLang'] ?? [];
    if (!empty($chkLang) && is_array($chkLang)) {
        $languages = implode(', ', array_map('htmlspecialchars', $chkLang));
    } else {
        $languages = 'Không có';
    }

    // 5. Lấy dữ liệu RadioButton Skill
    $skill = $_POST['radSkill'] ?? 'Chưa chọn';

    // 6. Lấy dữ liệu TextArea Note
    $note = $_POST['taNote'] ?? '';

    // 7. Lấy dữ liệu CheckBox đơn Marriage Status
    if (isset($_POST['chkMarriageStatus'])) {
        $marriageStatus = 'Đã kết hôn';
    } else {
        $marriageStatus = 'Chưa kết hôn';
    }
?>

    <div class="result-display-container">
        <div class="form-header-title">Thông tin Đăng ký đã nhận</div>

        <!-- 1. Username -->
        <div class="result-row">
            <div class="result-label">Username:</div>
            <div class="result-value"><?= htmlspecialchars($username) ?></div>
        </div>

        <!-- 2. Password -->
        <div class="result-row">
            <div class="result-label">Password:</div>
            <div class="result-value"><?= htmlspecialchars($password) ?></div>
        </div>

        <!-- 3. Gender -->
        <div class="result-row">
            <div class="result-label">Gender:</div>
            <div class="result-value"><?= htmlspecialchars($gender) ?></div>
        </div>

        <!-- 4. Address -->
        <div class="result-row">
            <div class="result-label">Address:</div>
            <div class="result-value"><?= htmlspecialchars($address) ?></div>
        </div>

        <!-- 5. Enable Programming Language -->
        <div class="result-row">
            <div class="result-label">Enable Programming Language:</div>
            <div class="result-value"><?= $languages ?></div>
        </div>

        <!-- 6. Skill -->
        <div class="result-row">
            <div class="result-label">Skill:</div>
            <div class="result-value"><?= htmlspecialchars($skill) ?></div>
        </div>

        <!-- 7. Note -->
        <div class="result-row">
            <div class="result-label">Note:</div>
            <div class="result-value"><?= !empty($note) ? nl2br(htmlspecialchars($note)) : '<i>(Không có ghi chú)</i>' ?></div>
        </div>

        <!-- 8. Marriage Status -->
        <div class="result-row">
            <div class="result-label">Marriage Status:</div>
            <div class="result-value"><?= htmlspecialchars($marriageStatus) ?></div>
        </div>

        <!-- Nút quay lại -->
        <div style="text-align: center; margin-top: 25px; padding-top: 15px; border-top: 1px solid #eaeaea;">
            <a href="index.php?page=register" class="btn-submit" style="display: inline-block; text-decoration: none; padding: 8px 24px;">
                ← Quay lại trang Đăng ký
            </a>
        </div>
    </div>

<?php
} else {
?>
    <div class="result-display-container" style="text-align: center;">
        <p style="color: #dc3545; font-weight: bold; margin-bottom: 20px;">
            Chưa có dữ liệu gửi lên từ form Đăng ký!
        </p>
        <a href="index.php?page=register" class="btn-submit" style="display: inline-block; text-decoration: none; padding: 8px 24px;">
            Đến trang Đăng ký
        </a>
    </div>
<?php
}
?>