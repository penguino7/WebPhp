<?php

/**
 * Trang Danh Sách Hãng Laptop (Categories List) - Bài 14
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/auth.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Render trang danh sách hãng laptop
 * @param mysqli $conn
 */
function renderCategoriesListPage($conn)
{
    $categories = getAllCategoriesWithCount($conn);
    $msg = $_GET['msg'] ?? '';
    $msgType = $_GET['msg_type'] ?? 'info';
?>
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">
                <span>📁</span> QUẢN LÝ DANH MỤC HÃNG LAPTOP
            </h2>
            <div class="header-tools">
                <a href="index.php?page=categories_form" class="btn-pixel btn-success-px">
                    ➕ Thêm Hãng Mới
                </a>
            </div>
        </div>

        <?php if (!empty($msg)): ?>
            <?php renderAlert($msgType, $msg); ?>
        <?php endif; ?>

        <?php if (empty($categories)): ?>
            <div class="empty-box">
                <p>Chưa có danh mục hãng nào trong hệ thống.</p>
                <a href="index.php?page=categories_form" class="btn-pixel btn-primary-px">➕ Thêm Hãng Đầu Tiên</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="pixel-table">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Tên Hãng Sản Xuất</th>
                            <th>Mô Tả / Giới Thiệu</th>
                            <th width="120" style="text-align:center;">Số Lượng Laptop</th>
                            <th width="150" style="text-align:center;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat):
                            $catId = (int)$cat['category_id'];
                            $count = (int)$cat['product_count'];
                        ?>
                            <tr>
                                <td><strong>#<?= $catId ?></strong></td>
                                <td>
                                    <strong style="color:var(--px-cyan); font-size:15px;"><?= htmlspecialchars($cat['category_name']) ?></strong>
                                </td>
                                <td>
                                    <span style="color:var(--px-text-muted); font-size:13.5px;"><?= htmlspecialchars($cat['description'] ?? 'Chưa có mô tả') ?></span>
                                </td>
                                <td style="text-align:center;">
                                    <span class="cat-badge" style="font-size:11px; padding:3px 8px;"><?= $count ?> máy</span>
                                </td>
                                <td>
                                    <div class="table-actions" style="justify-content:center;">
                                        <a href="index.php?page=categories_form&id=<?= $catId ?>" class="btn-pixel btn-warning-px" title="Chỉnh sửa hãng">
                                            ✏️ Sửa
                                        </a>
                                        <a href="index.php?page=categories_delete&id=<?= $catId ?>" onclick="return confirm('Bạn có chắc muốn xóa hãng \'<?= htmlspecialchars($cat['category_name']) ?>\'?');" class="btn-pixel btn-danger-px" title="Xóa hãng">
                                            🗑️ Xóa
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

renderCategoriesListPage($conn);
?>