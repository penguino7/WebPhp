<?php

/**
 * Trang Kết Quả Tìm Kiếm Sản Phẩm (Product Search) (Bài 13)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Render toàn bộ trang kết quả tìm kiếm
 * @param mysqli $conn
 * @param string $keyword
 * @param int $catId
 */
function renderProductSearchResultsPage($conn, $keyword = '', $catId = 0)
{
    $searchResults = searchProducts($conn, $keyword, $catId);
    $selectedCat = ($catId > 0) ? getCategoryById($conn, $catId) : null;
    $keywordSafe = htmlspecialchars($keyword);
    $totalFound = count($searchResults);
?>
    <!-- Breadcrumbs -->
    <div class="breadcrumb">
        <a href="index.php?page=home">Trang chủ</a> &raquo;
        <span>Kết quả tìm kiếm</span>
    </div>

    <!-- Search Results Header -->
    <div class="category-header-banner search-banner">
        <h2>Kết quả tìm kiếm sản phẩm</h2>
        <p>
            Từ khóa: <strong>"<?= $keywordSafe ?>"</strong>
            <?php if ($selectedCat): ?>
                | Hãng: <strong><?= htmlspecialchars($selectedCat['category_name']) ?></strong>
            <?php endif; ?>
        </p>
        <div class="filter-count">
            Tìm thấy <strong><?= $totalFound ?></strong> sản phẩm phù hợp
        </div>
    </div>

    <!-- Search Results Grid -->
    <?php if (empty($searchResults)): ?>
        <div class="empty-box">
            <div class="empty-icon">🔍</div>
            <h3>Không tìm thấy sản phẩm nào!</h3>
            <p>Rất tiếc, không có sản phẩm nào khớp với từ khóa "<strong><?= $keywordSafe ?></strong>".</p>
            <p>Gợi ý: Thử kiểm tra lỗi chính tả hoặc tìm với từ khóa chung hơn như <em>Dell, i5, RTX, MacBook, Asus</em>...</p>
            <div style="margin-top: 20px;">
                <a href="index.php?page=home" class="btn-back">⬅ Quay về trang chủ</a>
            </div>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($searchResults as $prod): ?>
                <?php renderProductCard($prod); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php
}

$searchKeyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$searchCatId = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
renderProductSearchResultsPage($conn, $searchKeyword, $searchCatId);
?>