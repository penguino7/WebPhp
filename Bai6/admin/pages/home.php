<h3 style="text-align: center; margin-bottom: 20px;">Thông tin Quản trị (Admin Home)</h3>

<div class="result-display-container">
    <div class="form-header-title">Thông tin người dùng đã đăng nhập</div>

    <div class="result-row">
        <div class="result-label">Tên đăng nhập (Session):</div>
        <div class="result-value"><?= htmlspecialchars($_SESSION['Username'] ?? '') ?></div>
    </div>

    <div class="result-row">
        <div class="result-label">Mật khẩu (Session):</div>
        <div class="result-value"><?= htmlspecialchars($_SESSION['Password'] ?? '') ?></div>
    </div>

    <div class="result-row">
        <div class="result-label">Thời điểm login (Cookie):</div>
        <div class="result-value">
            <?= htmlspecialchars($_COOKIE['lasttime'] ?? date('d/m/Y H:i:s')) ?>
        </div>
    </div>
</div>