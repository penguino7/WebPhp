<?php
$message = '';
$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Tài khoản mẫu: admin / 123
    if ($username === 'admin' && $password === '123') {
        $message = LOGIN_SUCCESS . htmlspecialchars($username) . '!';
    } else {
        $error = LOGIN_FAIL;
    }
}
?>

<div class="b10-content-wrap">
    <div class="b10-form-box b10-login-box">
        <div class="b10-form-header"><?= LOGIN_FORM_TITLE ?></div>

        <?php if (!empty($message)): ?>
            <div class="b10-alert-success">
                ✓ <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="b10-alert-error">
                ⚠ <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_USERNAME ?></label>
                <div class="b10-input-wrap">
                    <input type="text" name="username" class="b10-input" value="<?= htmlspecialchars($username) ?>" placeholder="admin" required>
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_PASSWORD ?></label>
                <div class="b10-input-wrap">
                    <input type="password" name="password" class="b10-input" placeholder="123" required>
                </div>
            </div>

            <div class="b10-btn-group">
                <button type="reset" class="b10-btn"><?= BTN_RESET ?></button>
                <button type="submit" class="b10-btn b10-btn-submit"><?= BTN_LOGIN ?></button>
            </div>
        </form>
    </div>
</div>