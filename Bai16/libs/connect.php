<?php

/**
 * Thư viện kết nối CSDL MySQL - Bài 16 (Tích Hợp Rich Text Box)
 */

if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_NAME')) define('DB_NAME', 'laptop_shop');

/**
 * Mở kết nối đến CSDL MySQL
 * @return mysqli
 */
function getDBConnection()
{
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die("<div style='color:#ff2a6d; font-family:Courier,monospace; padding:15px; background:#131124; border:3px solid #000;'>
                <strong>[CSDL ERROR]:</strong> " . mysqli_connect_error() . "<br>
                <em>Vui lòng đảm bảo MySQL trong XAMPP đang chạy và đã import CSDL!</em>
             </div>");
    }
    mysqli_set_charset($conn, "utf8mb4");
    return $conn;
}

/**
 * Đóng kết nối CSDL
 * @param mysqli|null $conn
 */
function closeDBConnection($conn)
{
    if ($conn instanceof mysqli) {
        @mysqli_close($conn);
    }
}
