<?php

/**
 * Header giao diện Quản Trị (Admin Header) - Bài 14
 */

require_once __DIR__ . '/../libs/auth.php';

$currentAdmin = getCurrentAdmin();
$adminName = htmlspecialchars($currentAdmin['fullname'] ?? 'Admin');
$adminRole = strtoupper(htmlspecialchars($currentAdmin['role'] ?? 'ADMIN'));
?>
<header class="admin-header">
    <div class="container admin-header-flex">
        <!-- Logo & Title -->
        <div class="admin-brand">
            <a href="index.php?page=dashboard">
                <span class="logo-icon">💻</span>
                <span class="brand-logo-text">LaptopShop <span style="color:var(--px-pink)">Admin</span></span>
                <span class="badge-admin-role"><?= $adminRole ?></span>
            </a>
        </div>

        <!-- Right Action Controls -->
        <div class="admin-header-actions">
            <!-- Current Admin User Badge -->
            <div class="admin-user-info">
                <span>👤 Xin chào:</span>
                <strong><?= $adminName ?></strong>
            </div>

            <!-- View Public Site Link -->
            <a href="../Bai13/index.php" target="_blank" class="btn-pixel btn-dark-px" title="Mở trang bán hàng End User">
                🌐 Xem Shop
            </a>

            <!-- Dark / Light Theme Toggle Button -->
            <button type="button" id="themeToggleBtn" class="btn-theme-toggle" onclick="togglePixelAdminTheme();" title="Chuyển chế độ Sáng / Tối">
                <span class="theme-icon">🌙</span>
                <span class="theme-text">TỐI</span>
            </button>

            <!-- Logout Button -->
            <a href="logout.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất khỏi trang Quản trị?');" class="btn-pixel btn-danger-px" title="Đăng xuất">
                🚪 Thoát
            </a>
        </div>
    </div>
</header>