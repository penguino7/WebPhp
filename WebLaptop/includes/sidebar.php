<?php

/**
 * Bài 15: Sidebar Danh Mục Hãng & Tiện Ích Giỏ Hàng
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$categories = getAllCategories($conn);
$currentCatId = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
$cartItems = getCartItems();
$cartCount = getCartTotalCount();
$cartTotal = getCartTotalPrice();
?>
<aside class="shop-sidebar">
    <!-- Hộp Danh Mục Hãng -->
    <div class="sidebar-box">
        <div class="sidebar-title">
            <span>🏷️</span> HÃNG SẢN XUẤT
        </div>
        <ul class="category-nav-list">
            <li class="<?= ($currentCatId === 0 && (!isset($_GET['page']) || $_GET['page'] === 'home')) ? 'active' : '' ?>">
                <a href="index.php?page=home">
                    <span>⚡ Tất Cả Sản Phẩm</span>
                </a>
            </li>
            <?php foreach ($categories as $cat): ?>
                <li class="<?= ($currentCatId === (int)$cat['category_id']) ? 'active' : '' ?>">
                    <a href="index.php?page=productList&cat_id=<?= $cat['category_id'] ?>">
                        <span>💻 <?= htmlspecialchars($cat['category_name']) ?></span>
                        <span class="cat-badge"><?= $cat['product_count'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Hộp Tóm Tắt Giỏ Hàng Nhanh -->
    <div class="sidebar-box">
        <div class="sidebar-title">
            <span>🛍️</span> GIỎ HÀNG CỦA BẠN
        </div>
        <div class="sidebar-cart-widget">
            <?php if (empty($cartItems)): ?>
                <p style="color: var(--text-muted); font-size: 0.85rem; text-align: center; padding: 10px 0;">
                    👾 Giỏ hàng đang trống!
                </p>
            <?php else: ?>
                <div class="sidebar-cart-row">
                    <span>Số lượng món:</span>
                    <strong style="color: var(--color-accent);"><?= $cartCount ?> cái</strong>
                </div>
                <div class="sidebar-cart-row">
                    <span>Tổng tiền:</span>
                    <strong style="color: var(--color-primary);"><?= formatPrice($cartTotal) ?></strong>
                </div>
                <div style="margin-top: 12px; display: flex; flex-direction: column; gap: 6px;">
                    <a href="index.php?page=cartView" class="pixel-btn pixel-btn-primary btn-block btn-sm">
                        👁️ XEM CHI TIẾT GIỎ
                    </a>
                    <a href="index.php?page=checkout" class="pixel-btn pixel-btn-success btn-block btn-sm">
                        💳 THANH TOÁN NGAY
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Hộp Liên Kết Dự Án -->
    <div class="sidebar-box">
        <div class="sidebar-title">
            <span>🔗</span> LIÊN KẾT NHANH
        </div>
        <ul class="category-nav-list">
            <li>
                <a href="../Bai16/index.php" target="_blank">
                    <span>📝 Quản Trị RichText (Bài 16)</span>
                </a>
            </li>
            <li>
                <a href="../Bai14/index.php" target="_blank">
                    <span>🛡️ Admin Panel (Bài 14)</span>
                </a>
            </li>
            <li>
                <a href="../Bai1/index.php">
                    <span>⬅ Về Menu Tổng</span>
                </a>
            </li>
        </ul>
    </div>
</aside>