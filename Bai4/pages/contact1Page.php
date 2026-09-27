<h3 style="text-align: center; margin-bottom: 20px;">Trang Liên hế</h3>

<?php

if (isset($_POST['btnContact'])) {
    // 1. Lấy dữ liệu từ form khi người dùng nhấn Contact
    $username = $_POST['txtUsername'] ?? '';
    $gender   = $_POST['radGender'] ?? 'Chưa chọn';
    $address  = $_POST['lstAddress'] ?? 'Chưa chọn';
    $note     = $_POST['taNote'] ?? '';
?>
    <!-- 2. HIỂN THỊ KẾT QUẢ (Ẩn form đi như yêu cầu đề bài) -->
    <div class="result-display-container">
        <div class="form-header-title">Thong tin lien he</div>

        <div class="result-row">
            <div class="result-label">Username:</div>
            <div class="result-value"><?= htmlspecialchars($username) ?></div>
        </div>

        <div class="result-row">
            <div class="result-label">Gender:</div>
            <div class="result-value"><?= htmlspecialchars($gender) ?></div>
        </div>

        <div class="result-row">
            <div class="result-label">Address:</div>
            <div class="result-value"><?= htmlspecialchars($address) ?></div>
        </div>

        <div class="result-row">
            <div class="result-label">Note:</div>
            <div class="result-value"><?= !empty($note) ? nl2br(htmlspecialchars($note)) : '<i>(Không có ghi chú)</i>' ?></div>
        </div>

        <div style="text-align: center; margin-top: 25px; padding-top: 15px; border-top: 1px solid #eaeaea;">
            <a href="index.php?page=contact1Page" class="btn-submit" style="display: inline-block; text-decoration: none; padding: 8px 24px;">
                ← Gửi liên hệ khác
            </a>
        </div>
    </div>

<?php } else { ?>
    <!-- 3. HIỂN THỊ FORM LIÊN HỆ (Khi chưa submit) -->
    <div class="getform-container">
        <div class="form-header-title">Form Lien he</div>

        <form method="POST" action="" class="getform-form">
            <!-- 1. Username -->
            <div class="form-group">
                <label class="form-label">Username:</label>
                <div class="form-control-wrap">
                    <input type="text" name="txtUsername" required>
                </div>
            </div>

            <!-- 2. Gender -->
            <div class="form-group">
                <label class="form-label">Gender:</label>
                <div class="form-control-wrap">
                    <div class="inline-group">
                        <label><input type="radio" name="radGender" value="Male" checked> Male</label>
                        <label><input type="radio" name="radGender" value="Female"> Female</label>
                    </div>
                </div>
            </div>

            <!-- 3. Address -->
            <div class="form-group">
                <label class="form-label">Address:</label>
                <div class="form-control-wrap">
                    <select name="lstAddress" size="4">
                        <option value="Ha Noi" selected>Ha Noi</option>
                        <option value="TP. HCM">TP. HCM</option>
                        <option value="Hue">Hue</option>
                        <option value="Da Nang">Da Nang</option>
                    </select>
                </div>
            </div>

            <!-- 4. Note -->
            <div class="form-group">
                <label class="form-label">Note:</label>
                <div class="form-control-wrap">
                    <textarea name="taNote" rows="3"></textarea>
                </div>
            </div>

            <!-- 5. Form Buttons -->
            <div class="form-buttons">
                <button type="button" class="btn-reset" onclick="window.location.href='index.php?page=contact1Page'">Reset</button>
                <button type="submit" name="btnContact" class="btn-submit">Contact</button>
            </div>
        </form>
    </div>
<?php } ?>