<?php

/**
 * Trang Chủ (Home) - Hiển thị Banner và mỗi Hãng 2 Laptop Mới Nhất (Bài 13)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Hiển thị các khối danh mục hãng laptop kèm 2 sản phẩm mới nhất
 * @param mysqli $conn
 */
function renderHomeCategorySections($conn)
{
    $homeSections = getHomeSectionsWithProducts($conn, 2);

    if (empty($homeSections)) {
        echo "<div class='empty-box'>
                <p>Chưa có sản phẩm laptop nào trong hệ thống CSDL.</p>
              </div>";
        return;
    }

    foreach ($homeSections as $sec) {
        $cat = $sec['category'];
        $products = $sec['products'];
        $catId = (int)$cat['category_id'];
        $catName = htmlspecialchars($cat['category_name']);
        $total = (int)$cat['total_products'];
?>
        <section class="category-block">
            <div class="category-block-header">
                <h2 class="category-block-title">
                    <span class="cat-icon">💻</span>
                    <?= $catName ?>
                </h2>
                <a href="index.php?page=productList&cat_id=<?= $catId ?>" class="view-all-link">
                    Xem tất cả (<?= $total ?>) &raquo;
                </a>
            </div>

            <div class="product-grid">
                <?php foreach ($products as $prod): ?>
                    <?php renderProductCard($prod); ?>
                <?php endforeach; ?>
            </div>
        </section>
<?php
    }
}
?>

<!-- Hero Banner Slider Area -->
<div class="hero-banner">
    <div class="banner-slide">
        <div class="banner-badge">🔥 KHUYẾN MÃI ĐẶC BIỆT</div>
        <h2>ĐÓN ĐẦU KỶ NGUYÊN LAPTOP AI</h2>
        <p>Sở hữu ngay các dòng máy trang bị chip Intel Core Ultra & Apple M3 với ưu đãi giảm giá đến 15% kèm quà tặng 2.000.000đ!</p>
        <div class="banner-actions">
            <a href="index.php?page=productList&cat_id=8" class="btn-banner-primary">Khám phá MacBook M3</a>
            <a href="index.php?page=productList&cat_id=1" class="btn-banner-secondary">Dell XPS Siêu Cấp</a>
        </div>
    </div>
</div>

<!-- Category Sections (Mỗi hãng hiển thị 2 sản phẩm mới nhất) -->
<div class="home-sections">
    <?php renderHomeCategorySections($conn); ?>
</div>