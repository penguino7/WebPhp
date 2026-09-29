<?php

/**
 * Bài 15: Danh Sách Laptop Theo Hãng (Product List)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$catId = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
$products = getProductsByCategory($conn, $catId);

$currentCategoryName = 'Tất Cả Sản Phẩm';
if ($catId > 0 && !empty($products)) {
    $currentCategoryName = $products[0]['category_name'] ?? 'Danh Mục Laptop';
}
?>

<div class="pixel-card">
    <div class="card-header-bar flex-between">
        <div>
            <h1 class="card-title">💻 <?= htmlspecialchars($currentCategoryName) ?></h1>
            <p class="card-subtitle">Hiển thị <strong style="color: var(--color-primary);"><?= count($products) ?></strong> mẫu laptop sẵn sàng đặt hàng</p>
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

    <?php if (empty($products)): ?>
        <p style="text-align: center; padding: 40px 0; color: var(--text-muted);">
            👾 Không tìm thấy laptop nào thuộc danh mục này!
        </p>
    <?php else: ?>
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
                            <a href="index.php?page=cartAdd&id=<?= $prod['product_id'] ?>&redirect=productList&cat_id=<?= $catId ?>" class="pixel-btn pixel-btn-success btn-sm" style="flex: 1;">
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
    <?php endif; ?>
</div>