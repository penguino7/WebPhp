<?php

/**
 * Thư viện Quản lý Phiên làm việc & Phân quyền - Bài 16
 */

require_once __DIR__ . '/connect.php';

/**
 * Khởi động Session an toàn
 */
function startAdminSession()
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }
}

/**
 * Kiểm tra xem người dùng đã đăng nhập Admin hay chưa
 * @return bool
 */
function isAdminLoggedIn()
{
    startAdminSession();
    return (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true);
}

/**
 * Lấy thông tin tài khoản hiện tại
 * @return array
 */
function getCurrentAdmin()
{
    startAdminSession();
    return $_SESSION['admin_user'] ?? [
        'username' => 'admin',
        'fullname' => 'Quản Trị Viên (Demo Mode)',
        'role'     => 'superadmin'
    ];
}

/**
 * Middleware Auth Guard: Tự động bảo vệ hoặc cho phép chế độ trải nghiệm
 */
function checkAdminAuth()
{
    startAdminSession();
    // Nếu chưa đăng nhập, tự động thiết lập phiên Demo Admin để người học dễ dàng trải nghiệm tính năng Rich Text Box
    if (!isAdminLoggedIn()) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = [
            'admin_id' => 1,
            'username' => 'admin',
            'fullname' => 'Quản Trị Viên Hệ Thống',
            'role'     => 'superadmin'
        ];
    }
}
