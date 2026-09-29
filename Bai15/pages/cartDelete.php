<?php

/**
 * Bài 15: Xử lý Xóa Sản Phẩm Khỏi Giỏ Hàng hoặc Làm Rỗng Giỏ Hàng
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$action = $_GET['action'] ?? 'remove';
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'clear') {
    clearCart();
    $msg = urlencode("Đã xóa sạch tất cả sản phẩm trong giỏ hàng!");
} elseif ($productId > 0) {
    removeFromCart($productId);
    $msg = urlencode("Đã xóa sản phẩm khỏi giỏ hàng thành công!");
} else {
    $msg = urlencode("Yêu cầu không hợp lệ!");
}

$targetUrl = "index.php?page=cartView&msg={$msg}&msg_type=info";

if (!headers_sent()) {
    header("Location: {$targetUrl}");
    exit();
} else {
    echo "<script>window.location.href = '{$targetUrl}';</script>";
    exit();
}
