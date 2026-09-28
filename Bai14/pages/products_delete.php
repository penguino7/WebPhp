<?php

/**
 * Xử Lý Xóa Sản Phẩm Laptop (Product Delete) - Bài 14
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/auth.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId > 0) {
    $res = deleteProduct($conn, $productId);
    $msgType = $res['success'] ? 'success' : 'danger';
    $encodedMsg = urlencode($res['message']);
    header("Location: index.php?page=products_list&msg={$encodedMsg}&msg_type={$msgType}");
    exit();
} else {
    header("Location: index.php?page=products_list&msg=" . urlencode("ID sản phẩm không hợp lệ!") . "&msg_type=danger");
    exit();
}
