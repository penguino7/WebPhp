<?php

/**
 * Bài 16: Trang Xem Trước Hiển Thị HTML (Rich Text Preview)
 * Minh họa kết quả render chuỗi HTML đã soạn thảo từ CKEditor
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/auth.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductById($conn, $productId);

if (!$product) {
    echo '<div class="pixel-card">';
    renderAlert('danger', 'Không tìm thấy sản phẩm laptop có ID: ' . $productId);
    echo '<div style="margin-top: 15px;"><a href="index.php?page=products_list" class="pixel-btn pixel-btn-primary">« Quay lại danh sách</a></div>';
    echo '</div>';
    return;
}

$imgSrc = 'images/' . (!empty($product['image']) ? $product['image'] : 'laptop_default.png');
?>

<div class="pixel-card">
    <div class="card-header-bar flex-between">
        <div>
            <h2 class="card-title">👁️ XEM TRƯỚC HIỂN THỊ NỘI DUNG RICH TEXT (HTML)</h2>
            <p class="card-subtitle">
                Sản phẩm: <strong style="color: var(--color-primary);"><?= htmlspecialchars($product['product_name']) ?></strong>
                (Hãng: <?= htmlspecialchars($product['category_name']) ?>)
            </p>
        </div>
        <div class="action-btn-group">
            <a href="index.php?page=products_form_richtext&id=<?= $product['product_id'] ?>" class="pixel-btn pixel-btn-warning">
                ✏️ SỬA TRONG CKEDITOR
            </a>
            <a href="index.php?page=products_list" class="pixel-btn pixel-btn-secondary">
                « DANH SÁCH
            </a>
        </div>
    </div>

    <!-- Thông tin cơ bản -->
    <div style="display: grid; grid-template-columns: 240px 1fr; gap: 24px; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 2px dashed var(--border-color);">
        <div>
            <img src="<?= htmlspecialchars($imgSrc) ?>"
                alt="<?= htmlspecialchars($product['product_name']) ?>"
                class="pixel-thumbnail"
                style="width: 100%; height: 180px; object-fit: contain; background: rgba(0,0,0,0.05); padding: 10px; border-radius: 6px;"
                onerror="this.onerror=null; this.src='images/laptop_default.png';">
        </div>

        <div>
            <h1 style="font-size: 1.5rem; margin-top: 0; margin-bottom: 8px; color: var(--text-color);">
                <?= htmlspecialchars($product['product_name']) ?>
            </h1>
            <div style="margin-bottom: 12px;">
                <span class="badge badge-brand"><?= htmlspecialchars($product['category_name']) ?></span>
                <span class="badge badge-primary">ID: #<?= $product['product_id'] ?></span>
            </div>
            <div style="font-size: 1.4rem; font-weight: 700; color: var(--color-primary); margin-bottom: 12px;">
                <?= formatPrice($product['price']) ?>
                <?php if (!empty($product['old_price']) && (float)$product['old_price'] > (float)$product['price']): ?>
                    <span style="font-size: 1rem; text-decoration: line-through; color: var(--text-muted); margin-left: 10px; font-weight: normal;">
                        <?= formatPrice($product['old_price']) ?>
                    </span>
                <?php endif; ?>
            </div>
            <?php if (!empty($product['summary_spec'])): ?>
                <div style="font-size: 0.9rem; color: var(--text-muted); background: var(--bg-card); padding: 10px 14px; border-left: 4px solid var(--color-accent);">
                    <strong>📋 Tóm tắt:</strong> <?= htmlspecialchars($product['summary_spec']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- KHU VỰC 1: HIỂN THỊ HTML ĐÃ RENDER (CHẤT LƯỢNG WYSIWYG) -->
    <div style="margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
            <span style="font-size: 1.2rem;">🖥️</span>
            <h3 style="margin: 0; color: var(--color-accent); font-size: 1.1rem; text-transform: uppercase;">
                1. Giao Diện Render Trực Tiếp (Kết Quả Hiển Thị Tới Khách Hàng)
            </h3>
        </div>

        <div class="richtext-rendered-content" style="background: var(--bg-card); border: 2px solid var(--border-color); border-radius: 8px; padding: 25px;">
            <?php
            if (!empty($product['full_spec'])) {
                // Xuất nội dung HTML đã được sanitize an toàn khi lưu trữ
                echo $product['full_spec'];
            } else {
                echo '<p style="color: var(--text-muted); font-style: italic;">Chưa có nội dung thông số kỹ thuật chi tiết bằng Rich Text Box.</p>';
            }
            ?>
        </div>
    </div>

    <!-- KHU VỰC 2: MÃ NGUỒN HTML GỐC (RAW HTML SOURCE CODE) -->
    <div>
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
            <span style="font-size: 1.2rem;">💻</span>
            <h3 style="margin: 0; color: var(--color-secondary); font-size: 1.1rem; text-transform: uppercase;">
                2. Mã Nguồn HTML Thô (Lưu Trong Trường `full_spec` CSDL)
            </h3>
        </div>

        <div style="position: relative;">
            <pre style="background: #111424; color: #00ffcc; padding: 18px; border-radius: 6px; overflow-x: auto; font-family: 'VT323', monospace; font-size: 1rem; line-height: 1.5; border: 1px solid #334466;"><code><?= htmlspecialchars($product['full_spec'] ?? '') ?></code></pre>
        </div>
    </div>
</div>