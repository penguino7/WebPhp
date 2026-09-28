<?php

/**
 * Thư viện Xác thực & Phân quyền Quản trị (Auth Guard Middleware) - Bài 14
 */

require_once __DIR__ . '/connect.php';

/**
 * Khởi động Session an toàn
 */
function startAdminSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Kiểm tra xem Admin đã đăng nhập hay chưa
 * @return bool
 */
function isAdminLoggedIn()
{
    startAdminSession();
    return (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true && !empty($_SESSION['admin_user']));
}

/**
 * Lấy thông tin tài khoản Admin đang đăng nhập
 * @return array|null
 */
function getCurrentAdmin()
{
    startAdminSession();
    return $_SESSION['admin_user'] ?? null;
}

/**
 * Middleware Auth Guard: Bắt buộc phải đăng nhập mới được vào trang quản trị
 * Nếu chưa đăng nhập, tự động chuyển hướng về trang login.php
 */
function checkAdminAuth()
{
    if (!isAdminLoggedIn()) {
        $redirectUrl = urlencode($_SERVER['REQUEST_URI'] ?? 'index.php');
        header("Location: login.php?msg=required&redirect={$redirectUrl}");
        exit();
    }
}

/**
 * Xử lý đăng nhập tài khoản Admin
 * @param mysqli $conn
 * @param string $username
 * @param string $password
 * @return array Mảng ['success' => bool, 'message' => string]
 */
function loginAdmin($conn, $username, $password)
{
    startAdminSession();

    $username = trim($username);
    $password = trim($password);

    if (empty($username) || empty($password)) {
        return [
            'success' => false,
            'message' => 'Vui lòng nhập đầy đủ Tên đăng nhập và Mật khẩu!'
        ];
    }

    $escapedUsername = mysqli_real_escape_string($conn, $username);
    $sql = "SELECT * FROM admins WHERE username = '{$escapedUsername}' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        // Kiểm tra mật khẩu (hỗ trợ password_hash BCRYPT và fallback an toàn)
        $isPasswordCorrect = false;
        if (password_verify($password, $admin['password'])) {
            $isPasswordCorrect = true;
        } elseif ($admin['password'] === md5($password) || $admin['password'] === $password) {
            $isPasswordCorrect = true;
        }

        if ($isPasswordCorrect) {
            // Chống tấn công Session Fixation bằng cách tạo lại Session ID
            session_regenerate_id(true);

            // Lưu thông tin vào Session
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_user'] = [
                'admin_id'   => (int)$admin['admin_id'],
                'username'   => $admin['username'],
                'fullname'   => $admin['fullname'],
                'email'      => $admin['email'],
                'role'       => $admin['role'],
                'login_time' => time()
            ];

            return [
                'success' => true,
                'message' => 'Đăng nhập thành công!'
            ];
        }
    }

    return [
        'success' => false,
        'message' => 'Tên đăng nhập hoặc Mật khẩu không chính xác!'
    ];
}

/**
 * Xử lý đăng xuất tài khoản Admin
 */
function logoutAdmin()
{
    startAdminSession();
    $_SESSION['admin_logged'] = false;
    unset($_SESSION['admin_user']);
    unset($_SESSION['admin_logged']);

    // Hủy toàn bộ Session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
}
