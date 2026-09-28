<?php

/**
 * Xử lý Đăng Xuất Quản Trị (Admin Logout) - Bài 14
 */

require_once __DIR__ . '/libs/auth.php';

logoutAdmin();

// Chuyển hướng về trang đăng nhập kèm thông báo
header("Location: login.php?logged_out=1");
exit();
