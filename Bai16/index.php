<?php

/**
 * Bài 16: Tích Hợp Rich Text Box (WYSIWYG Editor)
 * File điều hướng trung tâm (Router & Master Layout)
 */

// 1. Nạp các thư viện bắt buộc
require_once __DIR__ . '/libs/connect.php';
require_once __DIR__ . '/libs/auth.php';
require_once __DIR__ . '/libs/helper.php';

// 2. Kiểm tra quyền
checkAdminAuth();

// 3. Mở kết nối CSDL
$conn = getDBConnection();

// 4. Định tuyến trang
$page = isset($_GET['page']) ? trim($_GET['page']) : 'products_list';

$allowedPages = [
    'products_list'          => 'pages/products_list.php',
    'products_form_richtext' => 'pages/products_form_richtext.php',
    'products_delete'        => 'pages/products_delete.php',
    'product_preview'        => 'pages/product_preview.php'
];

$pageFile = $allowedPages[$page] ?? 'pages/products_list.php';

$pageTitles = [
    'products_list'          => 'Danh Sách Laptop (Bài 16 - Rich Text Box)',
    'products_form_richtext' => 'Soạn Thảo Thông Số Kỹ Thuật Bằng Rich Text Box',
    'products_delete'        => 'Xóa Laptop',
    'product_preview'        => 'Xem Trước Định Dạng HTML Rich Text'
];
$pageTitle = $pageTitles[$page] ?? 'Bài 16 - Tích Hợp Rich Text Box';
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
    <!-- CKEditor 4 WYSIWYG Script -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <!-- External JavaScript -->
    <script src="script.js" defer></script>
</head>

<body>

    <!-- Header -->
    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <!-- Layout Main -->
    <main class="admin-main-layout">
        <div class="container admin-grid">
            <!-- Sidebar -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- Content Area -->
            <div class="admin-content-area">
                <?php
                if (file_exists(__DIR__ . '/' . $pageFile)) {
                    require_once __DIR__ . '/' . $pageFile;
                } else {
                    renderAlert('danger', 'Trang yêu cầu không tồn tại!');
                }
                ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <?php
    // 5. Đóng kết nối CSDL
    closeDBConnection($conn);
    ?>
</body>

</html>