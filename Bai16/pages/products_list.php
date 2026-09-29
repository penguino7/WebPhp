<?php

/**
 * Bài 16: Danh Sách Laptop & Tích Hợp Rich Text WYSIWYG
 * Quản trị danh sách sản phẩm với liên kết soạn thảo Rich Text & Xem trước HTML
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/auth.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$catId = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;

$categories = getAllCategories($conn);
$data = getProductsWithRichText($conn, $keyword, $catId, $p, 8);
$products = $data['products'];
$totalPages = $data['total_pages'];
$currentPage = $data['current_page'];
$totalRecords = $data['total_records'];

// Xây dựng URL phân trang
$queryArgs = ['page' => 'products_list'];
if (!empty($keyword)) $queryArgs['keyword'] = $keyword;
if ($catId > 0) $queryArgs['cat_id'] = $catId;
$baseUrl = 'index.php?' . http_build_query($queryArgs);
?>

<div class="pixel-card">
    <div class="card-header-bar flex-between">
        <div>
            <h2 class="card-title">🎮 QUẢN LÝ LAPTOP &amp; RICH TEXT BOX</h2>
            <p class="card-subtitle">Soạn thảo bài viết, thông số kỹ thuật đa định dạng (bảng, hình ảnh, in đậm, danh sách) với CKEditor</p>
        </div>
        <div>
            <a href="index.php?page=products_form_richtext" class="pixel-btn pixel-btn-success">
                ➕ THÊM LAPTOP MỚI
            </a>
        </div>
    </div>

    <?php
    $msg = $_GET['msg'] ?? '';
    $msgType = $_GET['msg_type'] ?? 'info';
    if (!empty($msg)) {
        renderAlert($msgType, $msg);
    }
    ?>

    <!-- Thanh lọc & Tìm kiếm -->
    <form method="GET" action="index.php" class="filter-form-grid" style="margin-bottom: 20px;">
        <input type="hidden" name="page" value="products_list">

        <div class="form-group" style="margin-bottom: 0; flex: 2;">
            <input type="text" name="keyword" class="pixel-input" placeholder="🔍 Tìm theo tên hoặc thông số vắn tắt..." value="<?= htmlspecialchars($keyword) ?>">
        </div>

        <div class="form-group" style="margin-bottom: 0; flex: 1;">
            <select name="cat_id" class="pixel-input">
                <option value="0">-- Tất cả hãng --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>" <?= ($catId == $cat['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?> (<?= $cat['product_count'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="pixel-btn pixel-btn-primary">LỌC</button>
            <?php if (!empty($keyword) || $catId > 0): ?>
                <a href="index.php?page=products_list" class="pixel-btn pixel-btn-secondary">HỦY LỌC</a>
            <?php endif; ?>
        </div>
    </form>

    <div style="margin-bottom: 12px; font-size: 0.85rem; color: var(--text-muted);">
        📊 Tìm thấy <strong style="color: var(--color-primary);"><?= $totalRecords ?></strong> laptop
    </div>

    <!-- Bảng danh sách Laptop -->
    <div class="table-responsive">
        <table class="pixel-table">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 80px;">ẢNH</th>
                    <th>TÊN LAPTOP &amp; DANH MỤC</th>
                    <th style="width: 130px;">GIÁ BÁN</th>
                    <th style="width: 90px; text-align: center;">TỒN KHO</th>
                    <th>THÔNG SỐ RICH TEXT (HTML)</th>
                    <th style="width: 230px; text-align: center;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">
                            👾 Chưa có dữ liệu laptop nào phù hợp!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $prod):
                        $stock = isset($prod['quantity']) ? (int)$prod['quantity'] : 20;
                    ?>
                        <tr>
                            <td style="font-weight: 700; color: var(--color-accent);">#<?= $prod['product_id'] ?></td>
                            <td>
                                <?php
                                $imgSrc = 'images/' . (!empty($prod['image']) ? $prod['image'] : 'laptop_default.png');
                                ?>
                                <img src="<?= htmlspecialchars($imgSrc) ?>"
                                    alt="<?= htmlspecialchars($prod['product_name']) ?>"
                                    class="pixel-thumbnail"
                                    onerror="this.onerror=null; this.src='images/laptop_default.png';">
                            </td>
                            <td>
                                <strong style="color: var(--text-color);"><?= htmlspecialchars($prod['product_name']) ?></strong>
                                <br>
                                <span class="badge badge-brand"><?= htmlspecialchars($prod['category_name']) ?></span>
                            </td>
                            <td>
                                <div style="color: var(--color-primary); font-weight: 700;">
                                    <?= formatPrice($prod['price']) ?>
                                </div>
                                <?php if (!empty($prod['old_price']) && (float)$prod['old_price'] > (float)$prod['price']): ?>
                                    <div style="font-size: 0.75rem; text-decoration: line-through; color: var(--text-muted);">
                                        <?= formatPrice($prod['old_price']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($stock > 0): ?>
                                    <span class="badge" style="background: rgba(0, 255, 157, 0.15); color: var(--color-success); border: 1px solid var(--color-success); font-weight: 700;">
                                        📦 <?= $stock ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(255, 0, 85, 0.15); color: var(--color-danger); border: 1px solid var(--color-danger); font-weight: 700;">
                                        🚫 Hết
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-size: 0.8rem; color: var(--text-muted); max-height: 55px; overflow: hidden; line-height: 1.4;">
                                    <?php
                                    $previewText = strip_tags($prod['full_spec'] ?? '');
                                    if (empty($previewText)) {
                                        $previewText = $prod['summary_spec'] ?? '(Chưa có thông số chi tiết)';
                                    }
                                    echo htmlspecialchars(mb_substr($previewText, 0, 90)) . (mb_strlen($previewText) > 90 ? '...' : '');
                                    ?>
                                </div>
                                <?php if (!empty($prod['full_spec'])): ?>
                                    <span class="badge badge-primary" style="font-size: 0.65rem; margin-top: 4px;">✨ Có Rich Text</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="action-btn-group">
                                    <a href="index.php?page=product_preview&id=<?= $prod['product_id'] ?>"
                                        class="pixel-btn pixel-btn-info btn-sm"
                                        title="Xem trang hiển thị HTML">
                                        👁️ XEM HTML
                                    </a>
                                    <a href="index.php?page=products_form_richtext&id=<?= $prod['product_id'] ?>"
                                        class="pixel-btn pixel-btn-warning btn-sm"
                                        title="Sửa với CKEditor">
                                        ✏️ SỬA
                                    </a>
                                    <a href="index.php?page=products_delete&id=<?= $prod['product_id'] ?>"
                                        class="pixel-btn pixel-btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa Laptop: <?= addslashes($prod['product_name']) ?>?');"
                                        title="Xóa Laptop">
                                        🗑️
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <?php renderPagination($currentPage, $totalPages, $baseUrl); ?>
</div>