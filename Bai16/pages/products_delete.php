<?php

/**
 * Bài 16: Xử lý Xóa Laptop
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
    if ($res['success']) {
        echo "<script>
            alert('Đã xóa laptop thành công!');
            window.location.href = 'index.php?page=products_list';
        </script>";
        exit;
    } else {
        echo '<div class="pixel-card">';
        renderAlert('danger', $res['message']);
        echo '<div style="margin-top: 15px;"><a href="index.php?page=products_list" class="pixel-btn pixel-btn-primary">« Quay lại danh sách</a></div>';
        echo '</div>';
    }
} else {
    echo "<script>window.location.href = 'index.php?page=products_list';</script>";
    exit;
}
