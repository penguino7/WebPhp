<?php

/**
 * Bài 15: Trang Chủ (Home) - Hiển thị Laptop mới nhất theo từng hãng kèm nút Thêm Giỏ Hàng
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$groupedData = getProductsGroupedByCategory($conn, 2);
?>

<div class="pixel-card">
    <div class="card-header-bar flex-between">
        <div>
            <h1 class="card-title">🔥 BỘ SƯU TẬP LAPTOP NỔI BẬT</h1>
            <p class="card-subtitle">Khám phá các mẫu laptop gaming, văn phòng và đồ họa chính hãng hàng đầu</p>
        </div>
        <div>
            <a href="index.php?page=cartView" class="pixel-btn pixel-btn-primary">
                🛒 XEM GIỎ HÀNG (<?= getCartTotalCount() ?>)
            </a>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <?php renderAlert($_GET['msg_type'] ?? 'success', $_GET['msg']); ?>
    <?php endif; ?>

    <?php if (empty($groupedData)): ?>
        <p style="text-align: center; padding: 40px 0; color: var(--text-muted);">
            👾 Hiện chưa có sản phẩm laptop nào trong hệ thống!
        </p>
    <?php else: ?>
        <?php foreach ($groupedData as $group): ?>
            <?php
            $cat = $group['category'];
            $products = $group['products'];
            ?>
            <div class="category-section-title">
                <span>💻 <?= htmlspecialchars($cat['category_name']) ?></span>
                <a href="index.php?page=productList&cat_id=<?= $cat['category_id'] ?>" style="color: var(--color-primary); font-size: 0.75rem; text-decoration: none;">
                    Xem tất cả (<?= $cat['product_count'] ?>) »
                </a>
            </div>

            <div class="product-grid">
                <?php foreach ($products as $prod): ?>
                    <?php
                    $imgSrc = 'images/' . (!empty($prod['image']) ? $prod['image'] : 'laptop_default.png');
                    ?>
                    <div class="product-card">
                        <div>
                            <div class="product-thumb-box">
                                <a href="index.php?page=productDetail&id=<?= $prod['product_id'] ?>">
                                    <img src="<?= htmlspecialchars($imgSrc) ?>"
                                        alt="<?= htmlspecialchars($prod['product_name']) ?>"
                                        class="product-thumb"
                                        onerror="this.onerror=null; this.src='images/laptop_default.png';">
                                </a>
                            </div>

                            <span class="product-brand-tag"><?= htmlspecialchars($prod['category_name']) ?></span>

                            <h3>
                                <a href="index.php?page=productDetail&id=<?= $prod['product_id'] ?>" class="product-name" title="<?= htmlspecialchars($prod['product_name']) ?>">
                                    <?= htmlspecialchars($prod['product_name']) ?>
                                </a>
                            </h3>

                            <p class="product-spec-summary">
                                <?= htmlspecialchars(mb_substr($prod['summary_spec'], 0, 75)) ?><?= mb_strlen($prod['summary_spec']) > 75 ? '...' : '' ?>
                            </p>
                        </div>

                        <div>
                            <div class="product-price-box">
                                <span class="product-price"><?= formatPrice($prod['price']) ?></span>
                                <?php if (!empty($prod['old_price']) && (float)$prod['old_price'] > (float)$prod['price']): ?>
                                    <span class="product-old-price"><?= formatPrice($prod['old_price']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="product-actions">
                                <a href="index.php?page=cartAdd&id=<?= $prod['product_id'] ?>&redirect=home" class="pixel-btn pixel-btn-success btn-sm" style="flex: 1;">
                                    🛒 THÊM GIỎ
                                </a>
                                <a href="index.php?page=productDetail&id=<?= $prod['product_id'] ?>" class="pixel-btn pixel-btn-secondary btn-sm">
                                    👁️ CHI TIẾT
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>