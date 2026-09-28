<?php

/**
 * Header Quản Trị - Bài 16 (Tích Hợp Rich Text Box)
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
            <a href="index.php?page=products_list">
                <span class="logo-icon" style="font-size:28px; line-height:1;">📝</span>
                <span class="brand-logo-text">LaptopShop <span style="color:var(--px-pink)">RichText</span></span>
                <span class="badge-admin-role">BÀI 16</span>
            </a>
        </div>

        <!-- Right Action Controls -->
        <div class="admin-header-actions">
            <div style="background:var(--px-card-sub); border:2px solid var(--px-border); padding:5px 12px; font-size:13px; color:var(--px-yellow);">
                <span>👤</span> <strong><?= $adminName ?></strong>
            </div>

            <!-- View Public Site Link -->
            <a href="../Bai13/index.php" target="_blank" class="btn-pixel btn-dark-px" title="Xem Shop Bán Hàng">
                🌐 Xem Shop
            </a>

            <!-- Dark / Light Theme Toggle Button -->
            <button type="button" id="themeToggleBtn" class="btn-theme-toggle" onclick="togglePixelAdminTheme();" title="Chuyển chế độ Sáng / Tối">
                <span class="theme-icon">🌙</span>
                <span class="theme-text">TỐI</span>
            </button>
        </div>
    </div>
</header>