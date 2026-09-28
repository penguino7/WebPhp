<?php

/**
 * Xử Lý Xóa Danh Mục Hãng (Category Delete) - Bài 14
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/auth.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$catId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($catId > 0) {
    $res = deleteCategory($conn, $catId);
    $msgType = $res['success'] ? 'success' : 'danger';
    $encodedMsg = urlencode($res['message']);
    header("Location: index.php?page=categories_list&msg={$encodedMsg}&msg_type={$msgType}");
    exit();
} else {
    header("Location: index.php?page=categories_list&msg=" . urlencode("ID danh mục không hợp lệ!") . "&msg_type=danger");
    exit();
}
