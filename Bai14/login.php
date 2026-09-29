<?php

/**
 * Trang Đăng Nhập Quản Trị (Admin Login) - Bài 14
 */

require_once __DIR__ . '/libs/connect.php';
require_once __DIR__ . '/libs/auth.php';
require_once __DIR__ . '/libs/helper.php';

startAdminSession();

// Nếu đã đăng nhập thì tự động chuyển vào Dashboard
if (isAdminLoggedIn()) {
    header("Location: index.php");
    exit();
}

$conn = getDBConnection();
$errorMsg = '';
$successMsg = '';
$usernameVal = '';

// Kiểm tra thông báo từ URL query
if (isset($_GET['msg']) && $_GET['msg'] === 'required') {
    $errorMsg = 'Vui lòng đăng nhập để truy cập trang quản trị!';
}
if (isset($_GET['logged_out']) && $_GET['logged_out'] === '1') {
    $successMsg = 'Bạn đã đăng xuất khỏi hệ thống thành công!';
}

// Xử lý Submit Form đăng nhập
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $usernameVal = htmlspecialchars(trim($username));

    $result = loginAdmin($conn, $username, $password);
    if ($result['success']) {
        $redirectUrl = $_GET['redirect'] ?? 'index.php';
        header("Location: " . $redirectUrl);
        exit();
    } else {
        $errorMsg = $result['message'];
    }
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Quản Trị - LaptopShop Admin</title>
    <!-- Google Fonts Pixel -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400;500;600;700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
    <!-- Stylesheet -->
    <link rel="stylesheet" href="src/css/style.css">
    <!-- External JavaScript -->
    <script src="src/js/script.js" defer></script>
</head>

<body class="login-body">

    <div class="login-container">
        <!-- Pixel Theme Switcher -->
        <div class="login-theme-box">
            <button type="button" id="themeToggleBtn" class="btn-theme-toggle" onclick="togglePixelAdminTheme();" title="Chuyển chế độ Sáng / Tối">
                <span class="theme-icon">🌙</span>
                <span class="theme-text">TỐI</span>
            </button>
        </div>

        <div class="pixel-login-card">
            <div class="login-card-header">
                <div class="login-logo-icon">👾</div>
                <h1 class="login-title">ADMIN PANEL</h1>
                <p class="login-subtitle">Hệ Thống Quản Trị LaptopShop.vn</p>
            </div>

            <!-- Thông báo Alert -->
            <?php if (!empty($errorMsg)): ?>
                <?php renderAlert('danger', $errorMsg); ?>
            <?php endif; ?>

            <?php if (!empty($successMsg)): ?>
                <?php renderAlert('success', $successMsg); ?>
            <?php endif; ?>

            <!-- Form đăng nhập -->
            <form action="" method="POST" class="pixel-form login-form">
                <div class="form-group">
                    <label for="username">
                        <span class="label-icon">👤</span> TÊN ĐĂNG NHẬP:
                    </label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" value="<?= $usernameVal ?>" placeholder="Nhập username (vd: admin)..." required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">
                        <span class="label-icon">🔑</span> MẬT KHẨU:
                    </label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu (vd: admin123)..." required autocomplete="current-password">
                    </div>
                </div>

                <div class="form-actions-login">
                    <button type="submit" class="btn-pixel btn-login-submit">
                        <span class="btn-icon">⚡</span> ĐĂNG NHẬP [ENTER]
                    </button>
                </div>
            </form>

            <div class="login-card-footer">
                <div class="sample-credentials">
                    <div class="cred-title">🎮 TÀI KHOẢN MẪU:</div>
                    <code>User: <strong>admin</strong> | Pass: <strong>admin123</strong></code>
                </div>
                <div class="back-link-box">
                    <a href="../Bai13/index.php" class="pixel-link">⬅ Xem Website Bán Laptop (End User)</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>