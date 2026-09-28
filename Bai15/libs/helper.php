<?php
/**
 * Bài 15: Thư viện Truy Vấn CSDL & Hiển Thị Tiện Ích
 */

require_once __DIR__ . '/connect.php';

/**
 * Định dạng tiền tệ VNĐ
 * @param float|int|string|null $price
 * @return string
 */
function formatPrice($price)
{
    $priceNum = (float)$price;
    if ($priceNum <= 0) return "0 ₫";
    return number_format($priceNum, 0, ',', '.') . " ₫";
}

/**
 * Hiển thị thông báo Alert phong cách Pixel
 * @param string $type ('success' | 'danger' | 'warning' | 'info')
 * @param string $message
 */
function renderAlert($type, $message)
{
    if (empty($message)) return;
    $iconMap = [
        'success' => '✅',
        'danger'  => '❌',
        'warning' => '⚠️',
        'info'    => 'ℹ️'
    ];
    $icon = $iconMap[$type] ?? 'ℹ️';
    echo "<div class='pixel-alert pixel-alert-{$type}'>
            <span class='alert-icon'>{$icon}</span>
            <span class='alert-text'>" . htmlspecialchars($message) . "</span>
          </div>";
}

/**
 * Lấy tất cả danh mục hãng kèm số lượng sản phẩm
 * @param mysqli|null $conn
 * @return array
 */
function getAllCategories($conn)
{
    if (!$conn) return [];
    $sql = "SELECT c.*, COUNT(p.product_id) as product_count 
            FROM categories c 
            LEFT JOIN products p ON c.category_id = p.category_id 
            GROUP BY c.category_id, c.category_name 
            ORDER BY c.category_id ASC";
    $result = mysqli_query($conn, $sql);
    $list = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }
        mysqli_free_result($result);
    }
    return $list;
}

/**
 * Lấy danh sách sản phẩm gom nhóm theo từng danh mục (Mỗi danh mục lấy N sản phẩm mới nhất)
 * @param mysqli|null $conn
 * @param int $limitPerCat
 * @return array
 */
function getProductsGroupedByCategory($conn, $limitPerCat = 2)
{
    if (!$conn) return [];
    $categories = getAllCategories($conn);
    $grouped = [];

    foreach ($categories as $cat) {
        $catId = (int)$cat['category_id'];
        $sql = "SELECT p.*, c.category_name 
                FROM products p 
                INNER JOIN categories c ON p.category_id = c.category_id 
                WHERE p.category_id = {$catId} 
                ORDER BY p.product_id DESC 
                LIMIT {$limitPerCat}";
        $result = mysqli_query($conn, $sql);
        $products = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
            mysqli_free_result($result);
        }

        if (!empty($products)) {
            $grouped[] = [
                'category' => $cat,
                'products' => $products
            ];
        }
    }
    return $grouped;
}

/**
 * Lấy danh sách laptop theo hãng
 * @param mysqli|null $conn
 * @param int $catId
 * @return array
 */
function getProductsByCategory($conn, $catId)
{
    if (!$conn) return [];
    $catId = (int)$catId;
    $sql = "SELECT p.*, c.category_name 
            FROM products p 
            INNER JOIN categories c ON p.category_id = c.category_id 
            WHERE p.category_id = {$catId} 
            ORDER BY p.product_id DESC";
    $result = mysqli_query($conn, $sql);
    $list = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }
        mysqli_free_result($result);
    }
    return $list;
}

/**
 * Lấy chi tiết 1 sản phẩm laptop theo ID
 * @param mysqli|null $conn
 * @param int $productId
 * @return array|null
 */
function getProductById($conn, $productId)
{
    if (!$conn) return null;
    $productId = (int)$productId;
    $sql = "SELECT p.*, c.category_name 
            FROM products p 
            INNER JOIN categories c ON p.category_id = c.category_id 
            WHERE p.product_id = {$productId} 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row;
    }
    return null;
}

/**
 * Tìm kiếm sản phẩm theo từ khóa và danh mục
 * @param mysqli|null $conn
 * @param string $keyword
 * @param int $catId
 * @return array
 */
function searchProducts($conn, $keyword = '', $catId = 0)
{
    if (!$conn) return [];
    $keyword = trim($keyword);
    $escapedKeyword = mysqli_real_escape_string($conn, $keyword);
    $catId = (int)$catId;

    $where = " WHERE 1=1 ";
    if (!empty($keyword)) {
        $where .= " AND (p.product_name LIKE '%{$escapedKeyword}%' OR p.summary_spec LIKE '%{$escapedKeyword}%') ";
    }
    if ($catId > 0) {
        $where .= " AND p.category_id = {$catId} ";
    }

    $sql = "SELECT p.*, c.category_name 
            FROM products p 
            INNER JOIN categories c ON p.category_id = c.category_id 
            {$where} 
            ORDER BY p.product_id DESC";
    $result = mysqli_query($conn, $sql);
    $list = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }
        mysqli_free_result($result);
    }
    return $list;
}

/**
 * Lấy thông tin đơn hàng
 * @param mysqli|null $conn
 * @param int $orderId
 * @return array|null
 */
function getOrderById($conn, $orderId)
{
    if (!$conn) return null;
    $orderId = (int)$orderId;
    $sql = "SELECT * FROM orders WHERE order_id = {$orderId} LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row;
    }
    return null;
}

/**
 * Lấy danh sách chi tiết đơn hàng
 * @param mysqli|null $conn
 * @param int $orderId
 * @return array
 */
function getOrderDetails($conn, $orderId)
{
    if (!$conn) return [];
    $orderId = (int)$orderId;
    $sql = "SELECT * FROM order_details WHERE order_id = {$orderId} ORDER BY detail_id ASC";
    $result = mysqli_query($conn, $sql);
    $list = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $list[] = $row;
        }
        mysqli_free_result($result);
    }
    return $list;
}
