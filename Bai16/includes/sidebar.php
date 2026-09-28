<?php

/**
 * Sidebar Điều Hướng - Bài 16 (Tích Hợp Rich Text Box)
 */

$currentPage = $_GET['page'] ?? 'products_list';

/**
 * Render mục điều hướng sidebar
 * @param string $pageKey
 * @param string $label
 * @param string $icon
 * @param string $currentPage
 */
function renderNavItem($pageKey, $label, $icon, $currentPage)
{
    $isActive = ($currentPage === $pageKey) ? 'active' : '';
    echo "<li class='{$isActive}'>
            <a href='index.php?page={$pageKey}'>
                <span class='nav-icon'>{$icon}</span>
                <span class='nav-text'>{$label}</span>
            </a>
          </li>";
}
?>
<aside class="admin-sidebar">
    <!-- Menu Bài 16 -->
    <div class="sidebar-menu-box">
        <div class="sidebar-menu-header">
            <span>📝</span> RICH TEXT BOX
        </div>
        <ul class="admin-nav-list">
            <?php renderNavItem('products_list', 'Danh sách Laptop', '📦', $currentPage); ?>
            <?php renderNavItem('products_form_richtext', 'Thêm mới + RichText', '⚡', $currentPage); ?>
        </ul>
    </div>

    <!-- Tiện Ích Khác -->
    <div class="sidebar-menu-box">
        <div class="sidebar-menu-header">
            <span>🔗</span> LIÊN KẾT NHANH
        </div>
        <ul class="admin-nav-list">
            <li>
                <a href="../Bai14/index.php" target="_blank">
                    <span class="nav-icon">🛡️</span>
                    <span class="nav-text">Admin Panel (Bài 14)</span>
                </a>
            </li>
            <li>
                <a href="../Bai13/index.php" target="_blank">
                    <span class="nav-icon">🌐</span>
                    <span class="nav-text">Shop Khách Hàng (Bài 13)</span>
                </a>
            </li>
            <li>
                <a href="../Bai1/index.php">
                    <span class="nav-icon">⬅</span>
                    <span class="nav-text">Về Menu Tổng</span>
                </a>
            </li>
        </ul>
    </div>
</aside>