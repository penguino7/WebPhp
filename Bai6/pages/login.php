<h3 style="text-align: center; margin-bottom: 20px;">Trang Đăng nhập (Login with Cookie)</h3>

<?php
$error = '';

// 1. Đọc Cookie nếu đã từng lưu từ các lần đăng nhập trước
$savedUser = $_COOKIE['Username'] ?? '';
$savedPass = $_COOKIE['Password'] ?? '';
$isFromCookie = (!empty($savedUser) && !empty($savedPass));

// 2. Xử lý khi người dùng nhấn nút "Đăng Nhập"
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btnDangNhap'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Kiểm tra tài khoản admin / admin theo yêu cầu đề bài
    if ($username === 'admin' && $password === 'admin') {
        // A. Lưu vào Session cho phiên làm việc hiện tại
        $_SESSION['Username'] = $username;
        $_SESSION['Password'] = $password;

        // B. Lưu vào Cookies với thời hạn 30 ngày (30 * 24 * 3600 giây)
        setcookie("Username", $username, time() + 30 * 24 * 3600, "/");
        setcookie("Password", $password, time() + 30 * 24 * 3600, "/");
        setcookie("lasttime", date('d/m/Y H:i:s'), time() + 30 * 24 * 3600, "/");

        // C. Chuyển hướng vào trang quản trị Admin
        echo "<script>window.location.href='admin/index.php';</script>";
        exit();
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không chính xác!";
    }
}
?>

<div class="session-form-container">
    <div class="form-header-title">Dang nhap</div>

    <form method="POST" action="" class="login-form">
        <!-- 1. Ô nhập Username (Tự động điền nếu có Cookie) -->
        <div class="form-group">
            <label class="form-label">Username:</label>
            <div class="form-control-wrap">
                <input type="text" name="username" value="<?= htmlspecialchars($savedUser) ?>" required autocomplete="off">
            </div>
        </div>

        <!-- 2. Ô nhập Password (Tự động điền nếu có Cookie) -->
        <div class="form-group">
            <label class="form-label">Password:</label>
            <div class="form-control-wrap">
                <input type="password" name="password" value="<?= htmlspecialchars($savedPass) ?>" required>
            </div>
        </div>

        <!-- 3. Nút bấm điều khiển & Dòng thông báo Cookie -->
        <div class="form-buttons" style="flex-wrap: wrap;">
            <button type="button" class="btn-reset" onclick="window.location.href='index.php?page=login'">
                Nhap Lai
            </button>
            <button type="submit" name="btnDangNhap" class="btn-submit">
                Dang Nhap
            </button>

            <?php if ($isFromCookie): ?>
                <div style="width: 100%; text-align: center; margin-top: 10px;">
                    <span class="cookie-hint">username, password duoc lay tu Cookies</span>
                </div>
            <?php endif; ?>
        </div>
    </form>

    <!-- Hiển thị thông báo lỗi nếu đăng nhập sai -->
    <?php if (!empty($error)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
</div>