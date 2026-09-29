<?php

/**
 * Trang Tổng Quan Quản Trị (Admin Dashboard) - Bài 14
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/auth.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Render toàn bộ giao diện Dashboard
 * @param mysqli $conn
 */
function renderDashboardPage($conn)
{
    $stats = getDashboardStats($conn);
    $recentProducts = getRecentProducts($conn, 5);
?>
    <!-- Thẻ Tiêu Đề Dashboard -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">
                <span>📊</span> BẢNG ĐIỀU KHIỂN QUẢN TRỊ (DASHBOARD)
            </h2>
            <div class="header-tools">
                <a href="index.php?page=products_form" class="btn-pixel btn-success-px">
                    ⚡ + Thêm Laptop Mới
                </a>
            </div>
        </div>

        <!-- 4 Khối Thống Kê Nhanh (Stats Grid) -->
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-icon">💻</div>
                <div class="stat-info">
                    <span class="stat-value"><?= number_format($stats['total_products']) ?></span>
                    <span class="stat-label">Mẫu Laptop (Dòng máy)</span>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-icon">📦</div>
                <div class="stat-info">
                    <span class="stat-value"><?= number_format($stats['total_stock'] ?? 0) ?></span>
                    <span class="stat-label">Tổng tồn kho (Số máy)</span>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-icon">📁</div>
                <div class="stat-info">
                    <span class="stat-value"><?= number_format($stats['total_categories']) ?></span>
                    <span class="stat-label">Hãng sản xuất</span>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-icon">📈</div>
                <div class="stat-info">
                    <span class="stat-value" style="font-size:18px;"><?= formatPrice($stats['avg_price']) ?></span>
                    <span class="stat-label">Giá trung bình</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng Sản Phẩm Mới Thêm Gần Đây -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <span>🔥</span> SẢN PHẨM MỚI NHẤT TRONG HỆ THỐNG
            </h3>
            <a href="index.php?page=products_list" class="btn-pixel btn-dark-px">
                Xem toàn bộ (<?= $stats['total_products'] ?>) &raquo;
            </a>
        </div>

        <?php if (empty($recentProducts)): ?>
            <div class="empty-box">
                <p>Chưa có sản phẩm laptop nào trong hệ thống CSDL.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="pixel-table">
                    <thead>
                        <tr>
                            <th width="45">ID</th>
                            <th width="65">Ảnh</th>
                            <th>Tên Laptop</th>
                            <th width="130">Hãng</th>
                            <th width="120">Giá bán</th>
                            <th width="90" style="text-align:center;">Tồn kho</th>
                            <th>Cấu hình tóm tắt</th>
                            <th width="130" style="text-align:center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentProducts as $p):
                            $imgSrc = "../Bai13/images/" . htmlspecialchars($p['image']);
                            $pId = (int)$p['product_id'];
                            $stock = isset($p['quantity']) ? (int)$p['quantity'] : 20;
                        ?>
                            <tr>
                                <td><strong>#<?= $pId ?></strong></td>
                                <td>
                                    <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="table-img-thumb" onerror="this.onerror=null; this.src='../Bai13/images/laptop_default.png';">
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($p['product_name']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge-cert"><?= htmlspecialchars($p['category_name']) ?></span>
                                </td>
                                <td>
                                    <strong style="color:var(--px-price);"><?= formatPrice($p['price']) ?></strong>
                                </td>
                                <td style="text-align:center;">
                                    <?php if ($stock > 0): ?>
                                        <span class="badge-cert" style="background:rgba(0,255,157,0.12); color:var(--px-green); border:1px solid var(--px-green); padding:3px 6px; font-weight:700;">
                                            📦 <?= $stock ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-cert" style="background:rgba(255,0,85,0.12); color:var(--px-pink); border:1px solid var(--px-pink); padding:3px 6px; font-weight:700;">
                                            🚫 Hết
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="font-size:12px; color:var(--px-text-muted);"><?= htmlspecialchars($p['summary_spec']) ?></span>
                                </td>
                                <td>
                                    <div class="table-actions" style="justify-content:center;">
                                        <a href="index.php?page=products_form&id=<?= $pId ?>" class="btn-pixel btn-warning-px" title="Chỉnh sửa">
                                            ✏️ Sửa
                                        </a>
                                        <a href="index.php?page=products_delete&id=<?= $pId ?>" onclick="return confirm('Bạn có chắc muốn xóa laptop này?');" class="btn-pixel btn-danger-px" title="Xóa">
                                            🗑️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php
}

renderDashboardPage($conn);
?>