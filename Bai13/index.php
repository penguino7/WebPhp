<?php

/**
 * Bài 13: Xây dựng Website Bán Laptop (Phân hệ End User)
 * File điều hướng trung tâm (Router / Master Layout)
 */

// 1. Nạp cấu hình & thư viện
require_once __DIR__ . '/libs/connect.php';
require_once __DIR__ . '/libs/helper.php';

// 2. Mở kết nối CSDL
$conn = getDBConnection();

// 3. Định tuyến trang (Router)
$page = isset($_GET['page']) ? trim($_GET['page']) : 'home';

// Danh sách các trang hợp lệ được phép tải
$allowedPages = [
    'home'          => 'pages/home.php',
    'productList'   => 'pages/productList.php',
    'productDetail' => 'pages/productDetail.php',
    'productSearch' => 'pages/productSearch.php'
];

$pageFile = isset($allowedPages[$page]) ? $allowedPages[$page] : 'pages/home.php';

// Xác định tiêu đề trang
$pageTitles = [
    'home'          => 'LaptopShop.vn - Thế giới Laptop & Linh kiện chính hãng',
    'productList'   => 'Danh mục Laptop chính hãng - LaptopShop.vn',
    'productDetail' => 'Chi tiết sản phẩm Laptop - LaptopShop.vn',
    'productSearch' => 'Kết quả tìm kiếm sản phẩm - LaptopShop.vn'
];
$pageTitle = $pageTitles[$page] ?? 'LaptopShop.vn';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts Pixel -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400;500;600;700&family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
    <!-- Favicon & Stylesheet -->
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <!-- Inline theme detector to prevent flash of wrong theme -->
    <script>
        (function() {
            var currentTheme = localStorage.getItem('pixel_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', currentTheme);
        })();
    </script>
</head>

<body>

    <!-- Header Giao diện -->
    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <!-- Thân Trang (2 Cột: Sidebar + Main Content) -->
    <main class="site-main-layout">
        <div class="container layout-grid">
            <!-- Cột Trái: Menu Danh mục & Tiện ích -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- Cột Phải: Nội dung Động theo Router -->
            <div class="site-content">
                <?php
                if (file_exists(__DIR__ . '/' . $pageFile)) {
                    require_once __DIR__ . '/' . $pageFile;
                } else {
                    echo "<div class='empty-box'><p>Trang không tồn tại.</p></div>";
                }
                ?>
            </div>
        </div>
    </main>

    <!-- Footer Giao diện -->
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

        function togglePixelTheme() {
            var current = document.documentElement.getAttribute('data-theme') || 'dark';
            var next = (current === 'dark') ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('pixel_theme', next);
            updateThemeUI();
        }

        document.addEventListener('DOMContentLoaded', updateThemeUI);
    </script>

    <?php
    // 4. Đóng kết nối CSDL
    closeDBConnection($conn);
    ?>
</body>

</html>