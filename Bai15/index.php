<?php

/**
 * Bài 15: Xây Dựng Chức Năng Giỏ Hàng Cho Website Bán Máy Laptop (Shopping Cart)
 * File điều hướng trung tâm (Router & Master Layout)
 */

// 1. Nạp các thư viện bắt buộc
require_once __DIR__ . '/libs/connect.php';
require_once __DIR__ . '/libs/cart.php';
require_once __DIR__ . '/libs/helper.php';

// 2. Khởi tạo Session Giỏ hàng
startCartSession();
initCart();

// 3. Mở kết nối CSDL
$conn = getDBConnection();

// 4. Định tuyến trang
$page = isset($_GET['page']) ? trim($_GET['page']) : 'home';

$allowedPages = [
    'home'          => 'pages/home.php',
    'productList'   => 'pages/productList.php',
    'productDetail' => 'pages/productDetail.php',
    'productSearch' => 'pages/productSearch.php',
    'cartView'      => 'pages/cartView.php',
    'cartAdd'       => 'pages/cartAdd.php',
    'cartDelete'    => 'pages/cartDelete.php',
    'checkout'      => 'pages/checkout.php',
    'orderSuccess'  => 'pages/orderSuccess.php'
];

$pageFile = $allowedPages[$page] ?? 'pages/home.php';

// Xử lý các action chuyển hướng trước khi xuất HTML nếu cần
if ($page === 'cartAdd' || $page === 'cartDelete') {
    require_once __DIR__ . '/' . $pageFile;
    closeDBConnection($conn);
    exit();
}

$pageTitles = [
    'home'          => 'Trang Chủ - LaptopShop Arcade (Bài 15 Giỏ Hàng)',
    'productList'   => 'Danh Sách Laptop Theo Hãng - LaptopShop Arcade',
    'productDetail' => 'Chi Tiết Sản Phẩm & Đặt Mua - LaptopShop Arcade',
    'productSearch' => 'Tìm Kiếm Laptop - LaptopShop Arcade',
    'cartView'      => 'Giỏ Hàng Của Bạn - LaptopShop Arcade',
    'checkout'      => 'Đặt Hàng & Thanh Toán - LaptopShop Arcade',
    'orderSuccess'  => 'Đặt Hàng Thành Công - LaptopShop Arcade'
];
$pageTitle = $pageTitles[$page] ?? 'LaptopShop Arcade - Bài 15 Giỏ Hàng';
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
    <!-- External JavaScript -->
    <script src="script.js" defer></script>
</head>

<body>

    <!-- Header -->
    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <!-- Main Layout -->
    <main class="shop-main-layout">
        <div class="container shop-grid">
            <!-- Sidebar -->
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

            <!-- Content -->
            <div class="shop-content-area">
                <?php
                if (file_exists(__DIR__ . '/' . $pageFile)) {
                    require_once __DIR__ . '/' . $pageFile;
                } else {
                    renderAlert('danger', 'Trang bạn yêu cầu không tồn tại!');
                }
                ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <?php closeDBConnection($conn); ?>
</body>

</html>