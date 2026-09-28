<?php

/**
 * Trang Danh Sách Sản Phẩm Theo Hãng (Product List by Category) (Bài 13)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Render toàn bộ nội dung trang danh sách sản phẩm theo hãng
 * @param mysqli $conn
 * @param int $catId
 */
function renderProductListPage($conn, $catId)
{
    $category = getCategoryById($conn, $catId);

    if (!$category) {
        echo "<div class='alert-box empty-box'>
                <h3>⚠️ Không tìm thấy danh mục</h3>
                <p>Danh mục laptop bạn yêu cầu không tồn tại hoặc đã bị xóa.</p>
                <a href='index.php?page=home' class='btn-back'>⬅ Về trang chủ</a>
              </div>";
        return;
    }

    $products = getProductsByCategory($conn, $catId);
    $catName = htmlspecialchars($category['category_name']);
    $catDesc = htmlspecialchars($category['description'] ?? 'Danh sách các dòng máy tính xách tay chính hãng giá tốt.');
    $totalCount = count($products);
?>
    <!-- Breadcrumbs -->
    <div class="breadcrumb">
        <a href="index.php?page=home">Trang chủ</a> &raquo;
        <span><?= $catName ?></span>
    </div>

    <!-- Category Title Banner -->
    <div class="category-header-banner">
        <h2><?= $catName ?></h2>
        <p><?= $catDesc ?></p>
        <div class="filter-count">
            Tổng cộng: <strong><?= $totalCount ?></strong> sản phẩm
        </div>
    </div>

    <!-- Product Grid -->
    <?php if (empty($products)): ?>
        <div class="empty-box">
            <p>Hiện chưa có sản phẩm nào thuộc hãng <strong><?= $catName ?></strong>.</p>
            <a href="index.php?page=home" class="btn-back">⬅ Xem các dòng laptop khác</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $prod): ?>
                <?php renderProductCard($prod); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php
}

$pageCatId = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
renderProductListPage($conn, $pageCatId);
?>