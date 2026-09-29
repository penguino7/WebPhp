<?php

/**
 * Bài 15: Header Giao Diện Shop Khách Hàng (Tích Hợp Mini Cart Badge)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

$cartCount = getCartTotalCount();
$cartTotal = getCartTotalPrice();
?>
<header class="shop-header">
    <div class="container header-flex">
        <!-- Logo -->
        <a href="index.php?page=home" class="logo-link">
            <span class="logo-badge">🛒 BÀI 15</span>
            <span class="logo-title">LAPTOPSHOP ARCADE</span>
        </a>

        <!-- Thanh Tìm Kiếm Nhanh -->
        <div class="header-search-bar">
            <form method="GET" action="index.php" class="header-search-form">
                <input type="hidden" name="page" value="productSearch">
                <input type="text" name="keyword" class="search-input"
                    placeholder="🔍 Tìm laptop gaming, văn phòng, đồ họa..."
                    value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
                <button type="submit" class="pixel-btn pixel-btn-primary">TÌM</button>
            </form>
        </div>

        <!-- Hành Động: Giỏ Hàng & Theme -->
        <div class="header-actions">
            <!-- Nút Giỏ Hàng Nổi Bật -->
            <a href="index.php?page=cartView" class="cart-header-btn">
                <span>🛒 GIỎ HÀNG</span>
                <span class="cart-count-badge"><?= $cartCount ?></span>
                <span style="font-size: 0.8rem; color: var(--color-primary);">(<?= formatPrice($cartTotal) ?>)</span>
            </a>

            <!-- Theme Toggle -->
            <button type="button" class="pixel-btn pixel-btn-secondary" id="themeToggleBtn" onclick="togglePixelShopTheme()" title="Chuyển chế độ Sáng / Tối">
                <span class="theme-icon">🌙</span>
            </button>
        </div>
    </div>
</header>