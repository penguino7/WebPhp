<?php

/**
 * Bài 15: Chi Tiết Laptop (Product Detail) & Thêm Vào Giỏ Hàng Với Số Lượng
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductById($conn, $productId);

if (!$product) {
    echo '<div class="pixel-card">';
    renderAlert('danger', 'Không tìm thấy sản phẩm laptop có ID: ' . $productId);
    echo '<div style="margin-top: 15px;"><a href="index.php?page=home" class="pixel-btn pixel-btn-primary">« Quay lại trang chủ</a></div>';
    echo '</div>';
    return;
}

$imgSrc = 'images/' . (!empty($product['image']) ? $product['image'] : 'laptop_default.png');
?>

<div class="pixel-card">
    <div class="card-header-bar flex-between">
        <div>
            <h1 class="card-title">📖 CHI TIẾT SẢN PHẨM</h1>
            <p class="card-subtitle">Hãng sản xuất: <strong style="color: var(--color-primary);"><?= htmlspecialchars($product['category_name']) ?></strong></p>
        </div>
        <div>
            <a href="index.php?page=productList&cat_id=<?= $product['category_id'] ?>" class="pixel-btn pixel-btn-secondary">
                « CÁC MẪU CÙNG HÃNG
            </a>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <?php renderAlert($_GET['msg_type'] ?? 'success', $_GET['msg']); ?>
    <?php endif; ?>

    <!-- Chi tiết 2 cột -->
    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 28px; margin-bottom: 24px;">
        <!-- Cột Trái: Ảnh Lớn -->
        <div style="text-align: center; background: var(--bg-input); padding: 20px; border-radius: 8px; border: 2px solid var(--border-color);">
            <img src="<?= htmlspecialchars($imgSrc) ?>"
                alt="<?= htmlspecialchars($product['product_name']) ?>"
                style="width: 100%; height: 240px; object-fit: contain;"
                onerror="this.onerror=null; this.src='images/laptop_default.png';">
        </div>

        <!-- Cột Phải: Thông tin & Form Đặt Mua -->
        <div>
            <span class="product-brand-tag" style="font-size: 0.85rem;"><?= htmlspecialchars($product['category_name']) ?></span>
            <h2 style="font-size: 1.4rem; color: var(--text-heading); margin: 6px 0 12px 0;">
                <?= htmlspecialchars($product['product_name']) ?>
            </h2>

            <div style="margin-bottom: 16px;">
                <span class="product-price" style="font-size: 1.5rem;"><?= formatPrice($product['price']) ?></span>
                <?php if (!empty($product['old_price']) && (float)$product['old_price'] > (float)$product['price']): ?>
                    <span class="product-old-price" style="font-size: 1.1rem;"><?= formatPrice($product['old_price']) ?></span>
                    <span class="pixel-btn pixel-btn-danger btn-sm" style="margin-left: 8px; font-size: 0.75rem;">GIẢM GIÁ</span>
                <?php endif; ?>
            </div>

            <!-- Tóm tắt cấu hình -->
            <div style="background: var(--bg-card); padding: 12px 16px; border-left: 4px solid var(--color-primary); border-radius: 4px; margin-bottom: 20px;">
                <strong style="color: var(--color-primary);">📋 Cấu hình vắn tắt:</strong>
                <p style="margin-top: 4px; color: var(--text-color);"><?= htmlspecialchars($product['summary_spec']) ?></p>
            </div>

            <!-- Form Chọn Số Lượng & Thêm Vào Giỏ -->
            <form method="GET" action="index.php" style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <input type="hidden" name="page" value="cartAdd">
                <input type="hidden" name="id" value="<?= $product['product_id'] ?>">
                <input type="hidden" name="redirect" value="cartView">

                <div style="display: flex; align-items: center; gap: 8px;">
                    <label for="detailQuantity" style="font-weight: 700; font-size: 0.9rem;">SỐ LƯỢNG:</label>
                    <div class="qty-control-group">
                        <button type="button" class="qty-btn" onclick="adjustQty(-1)">-</button>
                        <input type="number" id="detailQuantity" name="quantity" value="1" min="1" max="99" class="qty-input">
                        <button type="button" class="qty-btn" onclick="adjustQty(1)">+</button>
                    </div>
                </div>

                <button type="submit" class="pixel-btn pixel-btn-success" style="padding: 10px 22px; font-size: 1rem;">
                    🛒 THÊM VÀO GIỎ HÀNG
                </button>
                <a href="index.php?page=checkout" class="pixel-btn pixel-btn-primary" style="padding: 10px 18px;">
                    ⚡ MUA NGAY
                </a>
            </form>
        </div>
    </div>

    <!-- Thông Số Kỹ Thuật Chi Tiết -->
    <div style="border-top: 2px dashed var(--border-color); padding-top: 20px; margin-top: 10px;">
        <h3 style="font-family: var(--font-heading); font-size: 0.85rem; color: var(--color-accent); margin-bottom: 12px;">
            📝 THÔNG SỐ KỸ THUẬT &amp; MÔ TẢ ĐẦY ĐỦ
        </h3>
        <div style="background: var(--bg-card); border: 2px solid var(--border-color); border-radius: 6px; padding: 18px; line-height: 1.6;">
            <?php if (!empty($product['full_spec'])): ?>
                <?= $product['full_spec'] ?>
            <?php else: ?>
                <p style="color: var(--text-muted); font-style: italic;">Chưa có bài viết mô tả chi tiết cho sản phẩm này.</p>
            <?php endif; ?>
        </div>
    </div>
</div>