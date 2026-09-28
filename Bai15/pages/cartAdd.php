<?php
/**
 * Bài 15: Xử lý Thêm Sản Phẩm Vào Giỏ Hàng (Cart Add Controller)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/cart.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$productId = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$quantity = isset($_REQUEST['quantity']) ? (int)$_REQUEST['quantity'] : 1;
if ($quantity < 1) $quantity = 1;

$redirect = $_REQUEST['redirect'] ?? 'cartView';
$targetUrl = 'index.php?page=cartView';

if ($productId > 0) {
    $product = getProductById($conn, $productId);
    if ($product) {
        addToCart($product, $quantity);
        $msg = urlencode("Đã thêm thành công {$quantity} máy '{$product['product_name']}' vào giỏ hàng!");
        
        if ($redirect === 'home') {
            $targetUrl = "index.php?page=home&msg={$msg}&msg_type=success";
        } elseif ($redirect === 'productList') {
            $catId = (int)($_REQUEST['cat_id'] ?? 0);
            $targetUrl = "index.php?page=productList&cat_id={$catId}&msg={$msg}&msg_type=success";
        } elseif ($redirect === 'productDetail') {
            $targetUrl = "index.php?page=productDetail&id={$productId}&msg={$msg}&msg_type=success";
        } elseif ($redirect === 'productSearch') {
            $kw = urlencode($_REQUEST['keyword'] ?? '');
            $catId = (int)($_REQUEST['cat_id'] ?? 0);
            $targetUrl = "index.php?page=productSearch&keyword={$kw}&cat_id={$catId}&msg={$msg}&msg_type=success";
        } else {
            $targetUrl = "index.php?page=cartView&msg={$msg}&msg_type=success";
        }
    } else {
        $msg = urlencode("Không tìm thấy thông tin sản phẩm laptop!");
        $targetUrl = "index.php?page=home&msg={$msg}&msg_type=danger";
    }
} else {
    $msg = urlencode("ID sản phẩm không hợp lệ!");
    $targetUrl = "index.php?page=home&msg={$msg}&msg_type=danger";
}

// Chuyển hướng an toàn
if (!headers_sent()) {
    header("Location: {$targetUrl}");
    exit();
} else {
    echo "<script>window.location.href = '{$targetUrl}';</script>";
    exit();
}
