<?php

/**
 * Bài 15: Trang Đặt Hàng Thành Công (Order Success Receipt)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = getOrderById($conn, $orderId);

if (!$order) {
    echo '<div class="pixel-card">';
    renderAlert('danger', 'Không tìm thấy đơn hàng #' . $orderId);
    echo '<div style="margin-top: 15px;"><a href="index.php?page=home" class="pixel-btn pixel-btn-primary">« Quay lại trang chủ</a></div>';
    echo '</div>';
    return;
}

$details = getOrderDetails($conn, $orderId);
?>

<div class="pixel-card" style="text-align: center; padding: 40px 20px;">
    <div style="font-size: 3.5rem; margin-bottom: 12px;">🎉</div>
    <h1 style="font-size: 1.5rem; color: var(--color-success); font-family: var(--font-heading); margin-bottom: 8px;">
        ĐẶT HÀNG THÀNH CÔNG!
    </h1>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 24px;">
        Cảm ơn bạn đã tin tưởng và mua sắm tại <strong>LaptopShop Arcade</strong>. Mã đơn hàng của bạn là <strong style="color: var(--color-accent);">#<?= $order['order_id'] ?></strong>.
    </p>

    <!-- Hộp Chi Tiết Hóa Đơn -->
    <div style="max-width: 720px; margin: 0 auto; text-align: left; background: var(--bg-card); border: 2px solid var(--border-color); border-radius: 8px; padding: 24px;">
        <h3 style="font-family: var(--font-heading); font-size: 0.8rem; color: var(--color-primary); border-bottom: 2px dashed var(--border-color); padding-bottom: 10px; margin-bottom: 16px;">
            📋 THÔNG TIN ĐƠN HÀNG #<?= $order['order_id'] ?>
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 0.9rem; margin-bottom: 20px;">
            <div>
                <span style="color: var(--text-muted);">Khách hàng:</span>
                <strong><?= htmlspecialchars($order['customer_name']) ?></strong>
            </div>
            <div>
                <span style="color: var(--text-muted);">Số điện thoại:</span>
                <strong><?= htmlspecialchars($order['customer_phone']) ?></strong>
            </div>
            <div>
                <span style="color: var(--text-muted);">Địa chỉ nhận hàng:</span>
                <strong><?= htmlspecialchars($order['customer_address']) ?></strong>
            </div>
            <div>
                <span style="color: var(--text-muted);">Phương thức thanh toán:</span>
                <strong style="color: var(--color-accent);"><?= ($order['payment_method'] === 'COD') ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản Ngân hàng' ?></strong>
            </div>
            <?php if (!empty($order['order_notes'])): ?>
                <div style="grid-column: span 2;">
                    <span style="color: var(--text-muted);">Ghi chú:</span>
                    <em><?= htmlspecialchars($order['order_notes']) ?></em>
                </div>
            <?php endif; ?>
        </div>

        <!-- Bảng Sản Phẩm Trong Đơn Hàng -->
        <h4 style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">📦 DANH SÁCH MÁY ĐÃ ĐẶT</h4>
        <div class="table-responsive">
            <table class="pixel-table">
                <thead>
                    <tr>
                        <th>TÊN LAPTOP</th>
                        <th style="width: 120px; text-align: right;">ĐƠN GIÁ</th>
                        <th style="width: 80px; text-align: center;">SỐ LƯỢNG</th>
                        <th style="width: 130px; text-align: right;">THÀNH TIỀN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $d): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($d['product_name']) ?></strong></td>
                            <td style="text-align: right;"><?= formatPrice($d['price']) ?></td>
                            <td style="text-align: center; font-weight: 700; color: var(--color-accent);"><?= $d['quantity'] ?></td>
                            <td style="text-align: right; font-weight: 700; color: var(--color-primary);"><?= formatPrice($d['subtotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 2px dashed var(--border-color); padding-top: 14px; margin-top: 14px;">
            <span style="font-size: 1rem; font-weight: 700;">TỔNG THANH TOÁN:</span>
            <span style="font-size: 1.3rem; font-weight: 700; color: var(--color-primary);"><?= formatPrice($order['total_amount']) ?></span>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <a href="index.php?page=home" class="pixel-btn pixel-btn-primary" style="padding: 12px 28px;">
            🛍️ TIẾP TỤC MUA SẮM LAPTOP KHÁC
        </a>
    </div>
</div>