<h3 style="text-align: center; margin-bottom: 20px;">Trang Đăng nhập (Login)</h3>


<?php

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btnLogin'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['Username'] = $username;
        $_SESSION['Password'] = $password;

        echo "<script>window.location.href='admin/index.php'</script>";
        exit();
    } else {
        $error = 'Tên đăng nhập hoặc mật khẩu không chính xác!';
    }
}
?>

<div class="session-form-container">
    <div class="form-header-title">Login</div>

    <form method="POST" action="" class="login-form">
        <!-- 1. Ô nhập Username -->
        <div class="form-group">
            <label class="form-label">Username:</label>
            <div class="form-control-wrap">
                <input type="text" name="username" required autocomplete="off">
            </div>
        </div>

        <!-- 2. Ô nhập Password -->
        <div class="form-group">
            <label class="form-label">Password:</label>
            <div class="form-control-wrap">
                <input type="password" name="password" required>
            </div>
        </div>

        <!-- 3. Các nút bấm điều khiển -->
        <div class="form-buttons">
            <button type="button" class="btn-reset" onclick="window.location.href='index.php?page=login'">
                Nhập Lại
            </button>
            <button type="submit" name="btnLogin" class="btn-submit">
                Đăng Nhập
            </button>
        </div>
    </form>

    <?php if (!empty($error)): ?>
        <div class="error-message">
            <?= $error ?>
        </div>
    <?php endif; ?>
</div>