<?php
/**
 * Bài 15: Tìm Kiếm Laptop (Product Search)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$catId = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
$categories = getAllCategories($conn);

$results = [];
if (!empty($keyword) || $catId > 0) {
    $results = searchProducts($conn, $keyword, $catId);
} else {
    // Nếu không tìm kiếm gì -> hiện tất cả sản phẩm
    $results = searchProducts($conn, '', 0);
}
?>

<div class="pixel-card">
    <div class="card-header-bar flex-between">
        <div>
            <h1 class="card-title">🔍 TÌM KIẾM SẢN PHẨM LAPTOP</h1>
            <p class="card-subtitle">
                <?php if (!empty($keyword)): ?>
                    Kết quả tìm kiếm cho từ khóa: <strong style="color: var(--color-primary);">"<?= htmlspecialchars($keyword) ?>"</strong>
                <?php else: ?>
                    Tìm kiếm theo từ khóa hoặc theo hãng sản xuất
                <?php endif; ?>
            </p>
        </div>
        <div>
            <a href="index.php?page=cartView" class="pixel-btn pixel-btn-primary">
                🛒 XEM GIỎ HÀNG (<?= getCartTotalCount() ?>)
            </a>
        </div>
    </div>

    <!-- Form Tìm Kiếm Chi Tiết -->
    <form method="GET" action="index.php" style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 10px; margin-bottom: 24px;">
        <input type="hidden" name="page" value="productSearch">
        <input type="text" name="keyword" class="pixel-input" 
               placeholder="Nhập tên laptop, chip CPU, RAM, card đồ họa..." 
               value="<?= htmlspecialchars($keyword) ?>">
        <select name="cat_id" class="pixel-input">
            <option value="0">-- Tất cả hãng sản xuất --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id'] ?>" <?= ($catId == $cat['category_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="pixel-btn pixel-btn-primary">
            🔍 TÌM KIẾM
        </button>
    </form>

    <div style="margin-bottom: 14px; font-size: 0.85rem; color: var(--text-muted);">
        📊 Tìm thấy <strong style="color: var(--color-primary);"><?= count($results) ?></strong> mẫu laptop phù hợp
    </div>

    <?php if (empty($results)): ?>
        <p style="text-align: center; padding: 40px 0; color: var(--text-muted);">
            👾 Không tìm thấy sản phẩm nào khớp với tiêu chí tìm kiếm của bạn!
        </p>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($results as $prod): ?>
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
                            <a href="index.php?page=cartAdd&id=<?= $prod['product_id'] ?>&redirect=productSearch&keyword=<?= urlencode($keyword) ?>&cat_id=<?= $catId ?>" class="pixel-btn pixel-btn-success btn-sm" style="flex: 1;">
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
