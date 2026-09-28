<?php

/**
 * Bài 14: Xây dựng Website Bán Laptop (Phân hệ Administration)
 * File điều hướng trung tâm (Admin Master Router)
 */

// 1. Nạp các thư viện bắt buộc
require_once __DIR__ . '/libs/connect.php';
require_once __DIR__ . '/libs/auth.php';
require_once __DIR__ . '/libs/helper.php';

// 2. Chặn truy cập nếu chưa đăng nhập (Auth Guard Middleware)
checkAdminAuth();

// 3. Mở kết nối CSDL
$conn = getDBConnection();

// 4. Định tuyến trang
$page = isset($_GET['page']) ? trim($_GET['page']) : 'dashboard';

// Danh sách trang hợp lệ được phép nạp
$allowedPages = [
    'dashboard'         => 'pages/dashboard.php',
    'categories_list'   => 'pages/categories_list.php',
    'categories_form'   => 'pages/categories_form.php',
    'categories_delete' => 'pages/categories_delete.php',
    'products_list'     => 'pages/products_list.php',
    'products_form'     => 'pages/products_form.php',
    'products_delete'   => 'pages/products_delete.php'
];

$pageFile = $allowedPages[$page] ?? 'pages/dashboard.php';

// Tiêu đề trang
$pageTitles = [
    'dashboard'         => 'Bảng Điều Khiển Quản Trị - LaptopShop Admin',
    'categories_list'   => 'Quản Lý Danh Mục Hãng - LaptopShop Admin',
    'categories_form'   => 'Thêm / Sửa Danh Mục Hãng - LaptopShop Admin',
    'categories_delete' => 'Xóa Danh Mục Hãng - LaptopShop Admin',
    'products_list'     => 'Quản Lý Sản Phẩm Laptop - LaptopShop Admin',
    'products_form'     => 'Thêm / Sửa Laptop - LaptopShop Admin',
    'products_delete'   => 'Xóa Laptop - LaptopShop Admin'
];
$pageTitle = $pageTitles[$page] ?? 'LaptopShop Admin Panel';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <!-- Google Fonts Pixel -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400;500;600;700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
    <!-- Stylesheet -->
    <link rel="stylesheet" href="style.css">
    <script>
        (function() {
            var currentTheme = localStorage.getItem('pixel_admin_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', currentTheme);
        })();
    </script>
</head>

<body>

    <!-- Header Giao diện Quản trị -->
    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <!-- Thân Trang Quản trị (2 Cột: Sidebar + Main Content) -->
    <main class="admin-main-layout">
        <div class="container admin-grid">
            <!-- Cột Trái: Menu Điều Hướng -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- Cột Phải: Nội Dung Động Theo Router -->
            <div class="admin-content-area">
                <?php
                if (file_exists(__DIR__ . '/' . $pageFile)) {
                    require_once __DIR__ . '/' . $pageFile;
                } else {
                    renderAlert('danger', 'Trang quản trị bạn yêu cầu không tồn tại!');
                }
                ?>
            </div>
        </div>
    </main>

    <!-- Footer Giao diện Quản trị -->
    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <!-- Theme Switcher Script -->
    <script>
        function updateThemeUI() {
            var theme = document.documentElement.getAttribute('data-theme') || 'dark';
            var btn = document.getElementById('themeToggleBtn');
            if (btn) {
                var icon = btn.querySelector('.theme-icon');
                var text = btn.querySelector('.theme-text');
                if (theme === 'light') {
                    if (icon) icon.textContent = '☀️';
                    if (text) text.textContent = 'SÁNG';
                } else {
                    if (icon) icon.textContent = '🌙';
                    if (text) text.textContent = 'TỐI';
                }
            }
        }

        function togglePixelAdminTheme() {
            var current = document.documentElement.getAttribute('data-theme') || 'dark';
            var next = (current === 'dark') ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('pixel_admin_theme', next);
            updateThemeUI();
        }

        document.addEventListener('DOMContentLoaded', updateThemeUI);
    </script>

    <?php
    // 5. Đóng kết nối CSDL
    closeDBConnection($conn);
    ?>
</body>

</html>