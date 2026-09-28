<?php

/**
 * Header giao diện Website Bán Laptop (Bài 13)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Render danh sách <option> hãng laptop cho ô tìm kiếm
 * @param mysqli $conn
 * @param int $selectedCatId
 */
function renderHeaderSearchCategories($conn, $selectedCatId = 0)
{
    $categories = getAllCategories($conn);
    foreach ($categories as $c) {
        $catId = (int)$c['category_id'];
        $catName = htmlspecialchars($c['category_name']);
        $selected = ($selectedCatId === $catId) ? 'selected' : '';
        echo "<option value='{$catId}' {$selected}>{$catName}</option>";
    }
}

/**
 * Render danh sách menu ngang các hãng nổi bật
 * @param mysqli $conn
 * @param string $currentPage
 * @param int $currentCatId
 */
function renderHeaderTopNav($conn, $currentPage = 'home', $currentCatId = 0)
{
    $categories = getAllCategories($conn);
    $topFive = array_slice($categories, 0, 5);

    foreach ($topFive as $topCat) {
        $catId = (int)$topCat['category_id'];
        $catName = htmlspecialchars($topCat['category_name']);
        $isActive = ($currentPage === 'productList' && $currentCatId === $catId) ? 'active' : '';
        echo "<li><a href='index.php?page=productList&cat_id={$catId}' class='{$isActive}'>{$catName}</a></li>";
    }
}

$currentCat = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
$keywordVal = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
$activePage = isset($_GET['page']) ? trim($_GET['page']) : 'home';
?>
<header class="site-header">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-content">
            <div class="top-contacts">
                <span>📞 Hotline: <strong>1800 6868</strong> (Miễn phí)</span>
                <span>⏰ 08:00 - 21:30 (Cả CN & Lễ)</span>
            </div>
            <div class="top-links">
                <a href="index.php?page=home">Trang chủ</a>
                <a href="#huongdan" onclick="alert('Trang Hướng dẫn mua hàng online!'); return false;">Hướng dẫn</a>
                <a href="#gioithieu" onclick="alert('LaptopShop.vn - Hệ thống bán lẻ laptop uy tín hàng đầu!'); return false;">Giới thiệu</a>
                <a href="#tuyendung" onclick="alert('LaptopShop.vn đang tuyển dụng nhân viên tư vấn & kỹ thuật!'); return false;">Tuyển dụng</a>
                <a href="#lienhe" onclick="alert('Liên hệ: 123 Phố Laptop, Hà Nội. Email: cskh@laptopshop.vn'); return false;">Liên hệ</a>
                <!-- Pixel Mode Switcher -->
                <button type="button" id="themeToggleBtn" class="btn-theme-toggle" onclick="togglePixelTheme();" title="Chuyển chế độ Sáng / Tối">
                    <span class="theme-icon">🌙</span>
                    <span class="theme-text">TỐI</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header">
        <div class="container header-flex">
            <!-- Brand Logo -->
            <div class="logo">
                <a href="index.php?page=home">
                    <div class="logo-icon">💻</div>
                    <div class="logo-text">
                        <span class="brand-name">LaptopShop<span class="brand-dot">.vn</span></span>
                        <span class="brand-tagline">Thế giới Laptop & Linh kiện chính hãng</span>
                    </div>
                </a>
            </div>

            <!-- Search Form -->
            <div class="search-section">
                <form action="index.php" method="GET" class="search-form">
                    <input type="hidden" name="page" value="productSearch">

                    <select name="cat_id" class="search-category-select">
                        <option value="0">Tất cả hãng sản xuất</option>
                        <?php renderHeaderSearchCategories($conn, $currentCat); ?>
                    </select>

                    <div class="search-input-wrapper">
                        <input type="text" name="keyword" value="<?= $keywordVal ?>" placeholder="Nhập tên laptop, cấu hình i5, i7, RTX, RAM 16GB..." required>
                        <button type="submit" class="btn-search">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>

            <!-- Header Quick Widget -->
            <div class="header-cart-box">
                <div class="cart-icon-wrapper" onclick="alert('Giỏ hàng hiện có 0 sản phẩm!');">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <span class="cart-count">0</span>
                </div>
                <div class="cart-info">
                    <span class="cart-label">Giỏ hàng</span>
                    <span class="cart-status">0 đ</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu Bar -->
    <nav class="main-navbar">
        <div class="container nav-container">
            <div class="nav-brands-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
                DANH MỤC HÃNG LAPTOP
            </div>
            <ul class="nav-links">
                <li><a href="index.php?page=home" class="<?= ($activePage === 'home') ? 'active' : '' ?>">Trang chủ</a></li>
                <?php renderHeaderTopNav($conn, $activePage, $currentCat); ?>
                <li><a href="../Bai1/index.php" style="color: #ffeb3b; font-weight: 600;">⬅ Về Menu Tổng</a></li>
            </ul>
        </div>
    </nav>
</header>