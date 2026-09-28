<?php

/**
 * Trang Chi Tiết Sản Phẩm Laptop (Product Detail) (Bài 13)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Render toàn bộ nội dung trang chi tiết laptop
 * @param mysqli $conn
 * @param int $productId
 */
function renderProductDetailPage($conn, $productId)
{
    $product = getProductById($conn, $productId);

    if (!$product) {
        echo "<div class='empty-box'>
                <h3>⚠️ Sản phẩm không tồn tại</h3>
                <p>Sản phẩm laptop bạn tìm kiếm không có trong hệ thống hoặc đã ngừng kinh doanh.</p>
                <a href='index.php?page=home' class='btn-back'>⬅ Về trang chủ</a>
              </div>";
        return;
    }

    $relatedProducts = getRelatedProducts($conn, (int)$product['category_id'], (int)$product['product_id'], 4);
    $imgSrc = "images/" . htmlspecialchars($product['image'] ?? 'laptop_default.png');
    $priceFormatted = formatPrice($product['price'] ?? 0);
    $oldPriceFormatted = !empty($product['old_price']) ? formatPrice($product['old_price']) : '';
    $discountPercent = 0;
    if (!empty($product['old_price']) && $product['old_price'] > $product['price']) {
        $discountPercent = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100);
    }
    $prodName = htmlspecialchars($product['product_name'] ?? '');
    $catName = htmlspecialchars($product['category_name'] ?? '');
    $catId = (int)$product['category_id'];
?>
    <!-- Breadcrumbs -->
    <div class="breadcrumb">
        <a href="index.php?page=home">Trang chủ</a> &raquo;
        <a href="index.php?page=productList&cat_id=<?= $catId ?>"><?= $catName ?></a> &raquo;
        <span><?= $prodName ?></span>
    </div>

    <!-- Product Detail Container -->
    <div class="product-detail-card">
        <div class="detail-top-grid">
            <!-- Cột Trái: Ảnh Sản Phẩm -->
            <div class="detail-gallery">
                <div class="detail-main-img">
                    <img src="<?= $imgSrc ?>" alt="<?= $prodName ?>" onerror="this.onerror=null;this.src='images/laptop_default.png';">
                </div>
                <div class="detail-guarantees">
                    <div class="guarantee-item">🛡️ Bảo hành chính hãng 12 - 24 tháng</div>
                    <div class="guarantee-item">🔄 1 đổi 1 trong 30 ngày nếu có lỗi NSX</div>
                    <div class="guarantee-item">🚚 Giao hàng miễn phí toàn quốc</div>
                    <div class="guarantee-item">🎁 Tặng Balo laptop cao cấp + Chuột không dây</div>
                </div>
            </div>

            <!-- Cột Phải: Thông Tin & Đặt Hàng -->
            <div class="detail-main-info">
                <h1 class="detail-title"><?= $prodName ?></h1>
                <div class="detail-brand-tag">
                    Hãng sản xuất: <a href="index.php?page=productList&cat_id=<?= $catId ?>"><strong><?= $catName ?></strong></a>
                    <span class="stock-status">🟢 Còn hàng</span>
                </div>

                <!-- Price Box -->
                <div class="detail-price-box">
                    <span class="detail-price-current"><?= $priceFormatted ?></span>
                    <?php if (!empty($oldPriceFormatted)): ?>
                        <span class="detail-price-old"><?= $oldPriceFormatted ?></span>
                        <span class="detail-discount-badge">Tiết kiệm <?= $discountPercent ?>%</span>
                    <?php endif; ?>
                    <div class="price-vat-note">(Giá đã bao gồm 10% VAT)</div>
                </div>

                <!-- Summary Specs Highlight -->
                <div class="detail-summary-specs">
                    <h4>Đặc điểm nổi bật:</h4>
                    <p><?= nl2br(htmlspecialchars($product['summary_spec'] ?? '')) ?></p>
                </div>

                <!-- Purchase CTAs -->
                <div class="detail-cta-box">
                    <button type="button" class="btn-buy-now" onclick="alert('Cảm ơn bạn! Đơn hàng laptop đã được ghi nhận.');">
                        <strong>MUA NGAY</strong>
                        <span>(Giao tận nơi hoặc nhận tại Showroom)</span>
                    </button>
                    <div class="cta-sub-row">
                        <button type="button" class="btn-add-cart" onclick="alert('Đã thêm sản phẩm vào giỏ hàng!');">
                            🛒 Thêm vào giỏ
                        </button>
                        <button type="button" class="btn-installment" onclick="alert('Hỗ trợ trả góp 0% qua thẻ tín dụng và CCCD!');">
                            Trả góp 0%
                        </button>
                    </div>
                </div>

                <div class="hotline-box">
                    📞 Hotline tư vấn miễn phí: <strong>1800 6868</strong> (8:00 - 21:30)
                </div>
            </div>
        </div>

        <!-- Cấu Hình Chi Tiết (Full Specs) -->
        <div class="detail-full-spec-section">
            <h3 class="section-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                    <polyline points="10 9 9 9 8 9" />
                </svg>
                THÔNG SỐ KỸ THUẬT CHI TIẾT
            </h3>
            <div class="spec-content-html">
                <?= $product['full_spec'] ?? '<p>Đang cập nhật thông số...</p>' ?>
            </div>
        </div>
    </div>

    <!-- Related Products Section -->
    <?php if (!empty($relatedProducts)): ?>
        <section class="category-block related-block">
            <div class="category-block-header">
                <h3 class="category-block-title">
                    <span>🔥</span> Sản phẩm cùng hãng <?= $catName ?>
                </h3>
            </div>
            <div class="product-grid">
                <?php foreach ($relatedProducts as $rel): ?>
                    <?php renderProductCard($rel); ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
<?php
}

$pageProductId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
renderProductDetailPage($conn, $pageProductId);
?>