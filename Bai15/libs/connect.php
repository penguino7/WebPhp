<?php
/**
 * Bài 15: Thư viện kết nối CSDL MySQL (laptop_shop)
 */

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'laptop_shop');
define('DB_PORT', 3306);

/**
 * Khởi tạo kết nối MySQL an toàn
 * @return mysqli|null
 */
function getDBConnection()
{
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if (!$conn) {
        $conn = @mysqli_connect('localhost', DB_USER, DB_PASS, DB_NAME);
    }
    if ($conn) {
        mysqli_set_charset($conn, 'utf8mb4');
    }
    return $conn;
}

/**
 * Đóng kết nối CSDL an toàn
 * @param mysqli|null $conn
 */
function closeDBConnection($conn)
{
    if ($conn && $conn instanceof mysqli) {
        @mysqli_close($conn);
    }
}
