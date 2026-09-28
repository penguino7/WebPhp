<?php

/**
 * Sidebar Menu Quản Trị (Admin Sidebar) - Bài 14
 */

$currentPage = $_GET['page'] ?? 'dashboard';

/**
 * Render mục điều hướng sidebar admin
 * @param string $pageKey
 * @param string $label
 * @param string $icon
 * @param string $currentPage
 */
function renderAdminNavItem($pageKey, $label, $icon, $currentPage)
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
    <!-- Menu Điều Hướng Chính -->
    <div class="sidebar-menu-box">
        <div class="sidebar-menu-header">
            <span>🎮</span> BẢNG ĐIỀU KHIỂN
        </div>
        <ul class="admin-nav-list">
            <?php renderAdminNavItem('dashboard', 'Tổng quan (Dashboard)', '📊', $currentPage); ?>
        </ul>
    </div>

    <!-- Quản Lý Danh Mục Hãng -->
    <div class="sidebar-menu-box">
        <div class="sidebar-menu-header">
            <span>📁</span> QUẢN LÝ HÃNG LAPTOP
        </div>
        <ul class="admin-nav-list">
            <?php renderAdminNavItem('categories_list', 'Danh sách Hãng máy', '📋', $currentPage); ?>
            <?php renderAdminNavItem('categories_form', 'Thêm Hãng mới', '➕', $currentPage); ?>
        </ul>
    </div>

    <!-- Quản Lý Sản Phẩm Laptop -->
    <div class="sidebar-menu-box">
        <div class="sidebar-menu-header">
            <span>💻</span> QUẢN LÝ LAPTOP
        </div>
        <ul class="admin-nav-list">
            <?php renderAdminNavItem('products_list', 'Danh sách Laptop', '📦', $currentPage); ?>
            <?php renderAdminNavItem('products_form', 'Thêm Laptop mới', '⚡', $currentPage); ?>
        </ul>
    </div>

    <!-- Tiện Ích Khác -->
    <div class="sidebar-menu-box">
        <div class="sidebar-menu-header">
            <span>⚙️</span> TIỆN ÍCH HỆ THỐNG
        </div>
        <ul class="admin-nav-list">
            <li>
                <a href="../Bai13/index.php" target="_blank">
                    <span class="nav-icon">🌐</span>
                    <span class="nav-text">Trang Khách hàng (B13)</span>
                </a>
            </li>
            <li>
                <a href="../Bai1/index.php">
                    <span class="nav-icon">⬅</span>
                    <span class="nav-text">Về Menu Tổng Bài Tập</span>
                </a>
            </li>
        </ul>
    </div>
</aside>