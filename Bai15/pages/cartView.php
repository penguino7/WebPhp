<?php

/**
 * Bài 15: Trang Quản Lý Giỏ Hàng (Cart View & Quantity Updates)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$message = '';
$messageType = '';

// Xử lý cập nhật số lượng qua POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    if (!empty($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $pId => $qty) {
            updateCartQuantity((int)$pId, (int)$qty);
        }
        $message = 'Đã cập nhật số lượng các món trong giỏ hàng thành công!';
        $messageType = 'success';
    }
}

$cartItems = getCartItems();
$cartCount = getCartTotalCount();
$cartTotal = getCartTotalPrice();
?>

<div class="pixel-card">
    <div class="card-header-bar flex-between">
        <div>
            <h1 class="card-title">🛒 GIỎ HÀNG CỦA BẠN</h1>
            <p class="card-subtitle">Quản lý các sản phẩm laptop bạn đã chọn trước khi tiến hành thanh toán</p>
        </div>
        <div>
            <a href="index.php?page=home" class="pixel-btn pixel-btn-secondary">
                🛍️ TIẾP TỤC MUA SẮM
            </a>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <?php renderAlert($_GET['msg_type'] ?? 'success', $_GET['msg']); ?>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <?php renderAlert($messageType, $message); ?>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 3.5rem; margin-bottom: 12px;">👾</div>
            <h2 style="font-size: 1.2rem; margin-bottom: 8px; color: var(--text-heading);">Giỏ hàng của bạn đang trống!</h2>
            <p style="color: var(--text-muted); margin-bottom: 24px;">Hãy dạo một vòng cửa hàng và chọn cho mình chiếc laptop ưng ý nhất nhé.</p>
            <a href="index.php?page=home" class="pixel-btn pixel-btn-primary" style="padding: 12px 24px;">
                🚀 KHÁM PHÁ SẢN PHẨM NGAY
            </a>
        </div>
    <?php else: ?>
        <form method="POST" action="index.php?page=cartView" id="cartForm">
            <input type="hidden" name="update_cart" value="1">

            <div class="table-responsive">
                <table class="pixel-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">STT</th>
                            <th style="width: 90px;">ẢNH</th>
                            <th>TÊN SẢN PHẨM LAPTOP</th>
                            <th style="width: 140px;">ĐƠN GIÁ</th>
                            <th style="width: 150px; text-align: center;">SỐ LƯỢNG</th>
                            <th style="width: 150px; text-align: right;">THÀNH TIỀN</th>
                            <th style="width: 80px; text-align: center;">XÓA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stt = 1;
                        foreach ($cartItems as $pId => $item):
                            $imgSrc = 'images/' . (!empty($item['image']) ? $item['image'] : 'laptop_default.png');
                            $subtotal = (float)$item['price'] * (int)$item['quantity'];
                        ?>
                            <tr>
                                <td style="font-weight: 700; color: var(--color-accent);"><?= $stt++ ?></td>
                                <td>
                                    <img src="<?= htmlspecialchars($imgSrc) ?>"
                                        alt="<?= htmlspecialchars($item['product_name']) ?>"
                                        style="width: 70px; height: 50px; object-fit: contain; background: var(--bg-input); padding: 4px; border-radius: 4px;"
                                        onerror="this.onerror=null; this.src='images/laptop_default.png';">
                                </td>
                                <td>
                                    <a href="index.php?page=productDetail&id=<?= $pId ?>" style="color: var(--text-color); font-weight: 700; text-decoration: none;">
                                        <?= htmlspecialchars($item['product_name']) ?>
                                    </a>
                                    <?php if (!empty($item['category_name'])): ?>
                                        <div style="font-size: 0.75rem; color: var(--color-secondary); font-weight: 700; margin-top: 2px;">
                                            <?= htmlspecialchars($item['category_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong style="color: var(--text-color);"><?= formatPrice($item['price']) ?></strong>
                                </td>
                                <td style="text-align: center;">
                                    <div class="qty-control-group">
                                        <button type="button" class="qty-btn" onclick="changeRowQty(<?= $pId ?>, -1)">-</button>
                                        <input type="number"
                                            id="qty_<?= $pId ?>"
                                            name="quantities[<?= $pId ?>]"
                                            value="<?= (int)$item['quantity'] ?>"
                                            min="1" max="<?= (int)($item['stock'] ?? 99) ?>"
                                            class="qty-input"
                                            onchange="document.getElementById('cartForm').submit();">
                                        <button type="button" class="qty-btn" onclick="changeRowQty(<?= $pId ?>, 1)">+</button>
                                    </div>
                                    <?php if (isset($item['stock'])): ?>
                                        <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px;">
                                            Kho: <?= (int)$item['stock'] ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right; font-weight: 700; color: var(--color-primary);">
                                    <?= formatPrice($subtotal) ?>
                                </td>
                                <td style="text-align: center;">
                                    <a href="index.php?page=cartDelete&id=<?= $pId ?>"
                                        class="pixel-btn pixel-btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xóa <?= addslashes($item['product_name']) ?> khỏi giỏ?');"
                                        title="Xóa món này">
                                        🗑️
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Thanh Nút Thao Tác Bảng Giỏ Hàng -->
            <div class="flex-between" style="margin-top: 10px; margin-bottom: 20px;">
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="pixel-btn pixel-btn-primary">
                        🔄 CẬP NHẬT GIỎ HÀNG
                    </button>
                    <a href="index.php?page=cartDelete&action=clear"
                        class="pixel-btn pixel-btn-danger"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa toàn bộ sản phẩm trong giỏ hàng?');">
                        🧹 XÓA SẠCH GIỎ HÀNG
                    </a>
                </div>
                <div>
                    <span style="font-size: 0.9rem; color: var(--text-muted);">
                        Tổng số lượng: <strong style="color: var(--color-accent);"><?= $cartCount ?> sản phẩm</strong>
                    </span>
                </div>
            </div>
        </form>

        <!-- Khung Tóm Tắt & Thanh Toán -->
        <div class="cart-summary-box">
            <div class="summary-row">
                <span>Tạm tính giỏ hàng:</span>
                <strong><?= formatPrice($cartTotal) ?></strong>
            </div>
            <div class="summary-row">
                <span>Phí vận chuyển toàn quốc:</span>
                <strong style="color: var(--color-success);">MIỄN PHÍ (0 ₫)</strong>
            </div>
            <div class="summary-row summary-total">
                <span>TỔNG TIỀN THANH TOÁN:</span>
                <span><?= formatPrice($cartTotal) ?></span>
            </div>

            <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
                <a href="index.php?page=home" class="pixel-btn pixel-btn-secondary" style="padding: 12px 20px;">
                    « MUA THÊM LAPTOP
                </a>
                <a href="index.php?page=checkout" class="pixel-btn pixel-btn-success" style="padding: 12px 28px; font-size: 1rem;">
                    💳 TIẾN HÀNH ĐẶT HÀNG »
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>