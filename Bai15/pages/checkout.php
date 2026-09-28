<?php
/**
 * Bài 15: Trang Đặt Hàng & Thanh Toán (Checkout Form & Order Processing)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$cartItems = getCartItems();
$cartCount = getCartTotalCount();
$cartTotal = getCartTotalPrice();

if (empty($cartItems)) {
    echo '<div class="pixel-card">';
    renderAlert('warning', 'Giỏ hàng của bạn đang trống! Vui lòng chọn sản phẩm trước khi thanh toán.');
    echo '<div style="margin-top: 15px;"><a href="index.php?page=home" class="pixel-btn pixel-btn-primary">« Quay lại mua sắm</a></div>';
    echo '</div>';
    return;
}

$errorMessage = '';

// Xử lý submit đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderData = [
        'customer_name'    => $_POST['customer_name'] ?? '',
        'customer_phone'   => $_POST['customer_phone'] ?? '',
        'customer_email'   => $_POST['customer_email'] ?? '',
        'customer_address' => $_POST['customer_address'] ?? '',
        'order_notes'      => $_POST['order_notes'] ?? '',
        'payment_method'   => $_POST['payment_method'] ?? 'COD'
    ];

    $res = saveOrderToDatabase($conn, $orderData, $cartItems);
    if ($res['success']) {
        $orderId = $res['order_id'];
        echo "<script>window.location.href = 'index.php?page=orderSuccess&id={$orderId}';</script>";
        exit;
    } else {
        $errorMessage = $res['message'];
    }
}
?>

<div class="pixel-card">
    <div class="card-header-bar flex-between">
        <div>
            <h1 class="card-title">💳 XÁC NHẬN ĐƠN HÀNG &amp; THANH TOÁN</h1>
            <p class="card-subtitle">Vui lòng điền đầy đủ thông tin giao hàng để chúng tôi xử lý đơn hàng nhanh nhất</p>
        </div>
        <div>
            <a href="index.php?page=cartView" class="pixel-btn pixel-btn-secondary">
                « SỬA GIỎ HÀNG
            </a>
        </div>
    </div>

    <?php if (!empty($errorMessage)): ?>
        <?php renderAlert('danger', $errorMessage); ?>
    <?php endif; ?>

    <form method="POST" action="index.php?page=checkout" class="checkout-grid">
        <!-- Cột Trái: Thông tin khách hàng -->
        <div>
            <h3 style="font-family: var(--font-heading); font-size: 0.85rem; color: var(--color-accent); margin-bottom: 16px;">
                1. THÔNG TIN NGƯỜI NHẬN HÀNG
            </h3>

            <div class="form-group">
                <label for="customer_name">HỌ VÀ TÊN <span style="color: var(--color-danger);">*</span></label>
                <input type="text" id="customer_name" name="customer_name" class="pixel-input" 
                       placeholder="VD: Nguyễn Văn An" 
                       value="<?= htmlspecialchars($_POST['customer_name'] ?? '') ?>" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label for="customer_phone">SỐ ĐIỆN THOẠI <span style="color: var(--color-danger);">*</span></label>
                    <input type="tel" id="customer_phone" name="customer_phone" class="pixel-input" 
                           placeholder="VD: 0987654321" 
                           value="<?= htmlspecialchars($_POST['customer_phone'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="customer_email">ĐỊA CHỈ EMAIL</label>
                    <input type="email" id="customer_email" name="customer_email" class="pixel-input" 
                           placeholder="VD: nguyenvanan@gmail.com" 
                           value="<?= htmlspecialchars($_POST['customer_email'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="customer_address">ĐỊA CHỈ NHẬN HÀNG <span style="color: var(--color-danger);">*</span></label>
                <input type="text" id="customer_address" name="customer_address" class="pixel-input" 
                       placeholder="VD: Số 123 Đường Cầu Giấy, Phường Dịch Vọng, Hà Nội" 
                       value="<?= htmlspecialchars($_POST['customer_address'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="order_notes">GHI CHÚ GIAO HÀNG (TÙY CHỌN)</label>
                <textarea id="order_notes" name="order_notes" class="pixel-input" rows="3" 
                          placeholder="VD: Giao hàng vào giờ hành chính, gọi trước khi đến..."><?= htmlspecialchars($_POST['order_notes'] ?? '') ?></textarea>
            </div>

            <h3 style="font-family: var(--font-heading); font-size: 0.85rem; color: var(--color-accent); margin: 20px 0 12px 0;">
                2. PHƯƠNG THỨC THANH TOÁN
            </h3>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label style="display: flex; align-items: center; gap: 10px; background: var(--bg-card); padding: 12px; border-radius: 6px; border: 2px solid var(--border-color); cursor: pointer;">
                    <input type="radio" name="payment_method" value="COD" checked>
                    <span>💵 <strong>Thanh toán tiền mặt khi nhận hàng (COD)</strong></span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; background: var(--bg-card); padding: 12px; border-radius: 6px; border: 2px solid var(--border-color); cursor: pointer;">
                    <input type="radio" name="payment_method" value="BankTransfer">
                    <span>🏦 <strong>Chuyển khoản Ngân hàng (QR Code / Internet Banking)</strong></span>
                </label>
            </div>

            <div style="margin-top: 24px;">
                <button type="submit" class="pixel-btn pixel-btn-success btn-block" style="padding: 14px; font-size: 1.05rem;">
                    🚀 HOÀN TẤT ĐẶT HÀNG (<?= formatPrice($cartTotal) ?>)
                </button>
            </div>
        </div>

        <!-- Cột Phải: Tóm tắt đơn hàng -->
        <div>
            <div style="background: var(--bg-card); border: 2px solid var(--border-color); border-radius: 8px; padding: 20px; position: sticky; top: 90px;">
                <h3 style="font-family: var(--font-heading); font-size: 0.8rem; color: var(--color-primary); margin-bottom: 14px; border-bottom: 1px dashed var(--border-color); padding-bottom: 10px;">
                    📦 TÓM TẮT ĐƠN HÀNG (<?= $cartCount ?> MÓN)
                </h3>

                <div style="max-height: 280px; overflow-y: auto; margin-bottom: 14px; padding-right: 4px;">
                    <?php foreach ($cartItems as $item): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-size: 0.85rem; border-bottom: 1px dashed var(--border-color); padding-bottom: 8px;">
                            <div style="flex: 1; padding-right: 8px;">
                                <strong style="color: var(--text-color);"><?= htmlspecialchars($item['product_name']) ?></strong>
                                <div style="color: var(--text-muted); font-size: 0.75rem;">
                                    <?= formatPrice($item['price']) ?> &times; <?= $item['quantity'] ?>
                                </div>
                            </div>
                            <div style="font-weight: 700; color: var(--color-primary);">
                                <?= formatPrice((float)$item['price'] * (int)$item['quantity']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="font-size: 0.9rem; margin-bottom: 8px; display: flex; justify-content: space-between;">
                    <span>Tạm tính:</span>
                    <strong><?= formatPrice($cartTotal) ?></strong>
                </div>
                <div style="font-size: 0.9rem; margin-bottom: 8px; display: flex; justify-content: space-between;">
                    <span>Phí vận chuyển:</span>
                    <strong style="color: var(--color-success);">0 ₫ (Miễn phí)</strong>
                </div>

                <div style="font-size: 1.15rem; font-weight: 700; color: var(--color-accent); border-top: 2px dashed var(--border-color); padding-top: 10px; margin-top: 10px; display: flex; justify-content: space-between;">
                    <span>TỔNG CỘNG:</span>
                    <span><?= formatPrice($cartTotal) ?></span>
                </div>
            </div>
        </div>
    </form>
</div>
