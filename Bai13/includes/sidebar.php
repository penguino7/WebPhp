<?php

/**
 * Left Sidebar: Menu Hãng laptop & Box Tiện ích (Bài 13)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Render danh sách hãng laptop trong sidebar
 * @param mysqli $conn
 * @param int $activeCatId
 */
function renderSidebarCategoryMenu($conn, $activeCatId = 0)
{
    $categories = getAllCategories($conn);
    foreach ($categories as $cat) {
        $catId = (int)$cat['category_id'];
        $catName = htmlspecialchars($cat['category_name']);
        $total = (int)$cat['total_products'];
        $isSel = ($activeCatId === $catId) ? 'class="active"' : '';

        echo "<li {$isSel}>
                <a href='index.php?page=productList&cat_id={$catId}'>
                    <span class='cat-name'>{$catName}</span>
                    <span class='cat-badge'>{$total}</span>
                </a>
              </li>";
    }
}

$sidebarActiveCatId = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
?>
<aside class="site-sidebar">
    <!-- Brand Categories Widget -->
    <div class="sidebar-box categories-box">
        <div class="sidebar-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                <line x1="8" y1="21" x2="16" y2="21" />
                <line x1="12" y1="17" x2="12" y2="21" />
            </svg>
            HÃNG SẢN XUẤT
        </div>
        <ul class="category-menu-list">
            <?php renderSidebarCategoryMenu($conn, $sidebarActiveCatId); ?>
        </ul>
    </div>

    <!-- Promo Banner / Hot Deals Widget -->
    <div class="sidebar-box promo-banner-box">
        <div class="promo-card">
            <span class="promo-tag">ƯU ĐÃI KHỦNG</span>
            <h4>TỰU TRƯỜNG 2026</h4>
            <p>Giảm thêm đến <strong>1.500.000đ</strong> cho Học sinh - Sinh viên khi mua Laptop!</p>
            <a href="javascript:alert('Áp dụng tại mọi chi nhánh trên toàn quốc!');" class="btn-promo-action">Nhận mã ngay</a>
        </div>
    </div>

    <!-- Customer Support Widget -->
    <div class="sidebar-box support-box">
        <div class="sidebar-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
            </svg>
            HỖ TRỢ KHÁCH HÀNG
        </div>
        <div class="support-content">
            <div class="support-item">
                <strong>Tư vấn bán hàng:</strong>
                <span class="support-phone">1800 6868</span>
            </div>
            <div class="support-item">
                <strong>Hỗ trợ kỹ thuật:</strong>
                <span class="support-phone">1800 6869</span>
            </div>
            <div class="support-item">
                <strong>Email hỗ trợ:</strong>
                <span>cskh@laptopshop.vn</span>
            </div>
        </div>
    </div>
</aside>