<?php

/**
 * Thư viện các hàm CRUD, Xử lý HTML an toàn & Tiện ích Rich Text Box - Bài 16
 */

require_once __DIR__ . '/connect.php';

// ==========================================================================
// 1. CÁC HÀM XỬ LÝ NỘI DUNG HTML & CHỐNG XSS
// ==========================================================================

/**
 * Làm sạch chuỗi HTML từ Rich Text Box:
 * Cho phép các thẻ định dạng, bảng, ảnh, danh sách, nhưng loại bỏ các thẻ script/iframe độc hại
 * @param string $html
 * @return string
 */
function sanitizeRichTextHtml($html)
{
    if (empty($html)) return '';

    // Danh sách các thẻ an toàn được phép lưu trữ
    $allowedTags = '<h1><h2><h3><h4><h5><h6><p><br><hr><b><strong><i><em><u><s><strike><ul><ol><li><table><thead><tbody><tfoot><tr><th><td><span><div><img><a><blockquote><code><pre>';

    // 1. Loại bỏ thẻ không được phép
    $cleaned = strip_tags($html, $allowedTags);

    // 2. Loại bỏ các thuộc tính inline JavaScript nguy hiểm (onload, onerror, onclick, onmouseover...)
    $cleaned = preg_replace('/\s+on[a-z]+\s*=\s*(["\']).*?\1/i', '', $cleaned);
    $cleaned = preg_replace('/\s+on[a-z]+\s*=\s*[^ >]+/i', '', $cleaned);

    // 3. Loại bỏ giao thức nguy hiểm trong href/src
    $cleaned = preg_replace('/href\s*=\s*(["\'])\s*javascript:[^"\']*\1/i', 'href="#"', $cleaned);
    $cleaned = preg_replace('/src\s*=\s*(["\'])\s*javascript:[^"\']*\1/i', '', $cleaned);

    return $cleaned;
}

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

// ==========================================================================
// 2. CÁC HÀM TRUY VẤN CSDL CHO BÀI 16
// ==========================================================================

/**
 * Lấy tất cả danh mục hãng
 * @param mysqli $conn
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
 * Lấy danh sách sản phẩm có phân trang & tìm kiếm
 * @param mysqli $conn
 * @param string $keyword
 * @param int $catId
 * @param int $page
 * @param int $limit
 * @return array
 */
function getProductsWithRichText($conn, $keyword = '', $catId = 0, $page = 1, $limit = 8)
{
    if (!$conn) {
        return [
            'products'      => [],
            'total_records' => 0,
            'total_pages'   => 1,
            'current_page'  => 1
        ];
    }

    $keyword = trim($keyword);
    $escapedKeyword = mysqli_real_escape_string($conn, $keyword);
    $catId = (int)$catId;
    $page = max(1, (int)$page);
    $limit = max(1, (int)$limit);

    $where = " WHERE 1=1 ";
    if (!empty($keyword)) {
        $where .= " AND (p.product_name LIKE '%{$escapedKeyword}%' OR p.summary_spec LIKE '%{$escapedKeyword}%') ";
    }
    if ($catId > 0) {
        $where .= " AND p.category_id = {$catId} ";
    }

    // 1. Đếm tổng số bản ghi
    $countSql = "SELECT COUNT(*) as total FROM products p {$where}";
    $countRes = mysqli_query($conn, $countSql);
    $totalRecords = 0;
    if ($countRes && $row = mysqli_fetch_assoc($countRes)) {
        $totalRecords = (int)$row['total'];
        mysqli_free_result($countRes);
    }

    $totalPages = ceil($totalRecords / $limit);
    if ($totalPages < 1) $totalPages = 1;
    if ($page > $totalPages) $page = $totalPages;

    $offset = ($page - 1) * $limit;

    // 2. Lấy danh sách sản phẩm
    $sql = "SELECT p.*, c.category_name 
            FROM products p 
            INNER JOIN categories c ON p.category_id = c.category_id 
            {$where} 
            ORDER BY p.product_id DESC 
            LIMIT {$offset}, {$limit}";

    $result = mysqli_query($conn, $sql);
    $products = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        mysqli_free_result($result);
    }

    return [
        'products'      => $products,
        'total_records' => $totalRecords,
        'total_pages'   => $totalPages,
        'current_page'  => $page
    ];
}

/**
 * Lấy chi tiết 1 sản phẩm laptop theo ID
 * @param mysqli $conn
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
 * Thêm mới Laptop kèm nội dung Rich Text HTML
 * @param mysqli $conn
 * @param array $data
 * @return array ['success' => bool, 'message' => string, 'insert_id' => int]
 */
function insertProductRichText($conn, $data)
{
    $name = trim($data['product_name'] ?? '');
    $catId = (int)($data['category_id'] ?? 0);
    $price = (float)($data['price'] ?? 0);
    $oldPrice = !empty($data['old_price']) ? (float)$data['old_price'] : "NULL";
    $quantity = isset($data['quantity']) ? max(0, (int)$data['quantity']) : 20;
    $image = trim($data['image'] ?? 'laptop_default.png');
    $summary = trim($data['summary_spec'] ?? '');

    // Xử lý làm sạch chuỗi HTML từ Rich Text Box
    $fullSpecRaw = $data['full_spec'] ?? '';
    $fullSpecClean = sanitizeRichTextHtml($fullSpecRaw);

    if (empty($name)) {
        return ['success' => false, 'message' => 'Tên sản phẩm laptop không được để trống!', 'insert_id' => 0];
    }
    if ($catId <= 0) {
        return ['success' => false, 'message' => 'Vui lòng chọn danh mục hãng sản xuất!', 'insert_id' => 0];
    }
    if ($price <= 0) {
        return ['success' => false, 'message' => 'Giá bán phải lớn hơn 0!', 'insert_id' => 0];
    }

    $escapedName = mysqli_real_escape_string($conn, $name);
    $escapedImage = mysqli_real_escape_string($conn, $image);
    $escapedSummary = mysqli_real_escape_string($conn, $summary);
    $escapedFullSpec = mysqli_real_escape_string($conn, $fullSpecClean);
    $oldPriceSql = ($oldPrice === "NULL") ? "NULL" : "'{$oldPrice}'";

    $sql = "INSERT INTO products (category_id, product_name, price, old_price, quantity, image, summary_spec, full_spec) 
            VALUES ({$catId}, '{$escapedName}', {$price}, {$oldPriceSql}, {$quantity}, '{$escapedImage}', '{$escapedSummary}', '{$escapedFullSpec}')";

    if (mysqli_query($conn, $sql)) {
        $newId = mysqli_insert_id($conn);
        return ['success' => true, 'message' => 'Thêm mới Laptop với định dạng Rich Text thành công!', 'insert_id' => $newId];
    }

    return ['success' => false, 'message' => 'Lỗi thêm sản phẩm: ' . mysqli_error($conn), 'insert_id' => 0];
}

/**
 * Cập nhật Laptop kèm nội dung Rich Text HTML
 * @param mysqli $conn
 * @param int $productId
 * @param array $data
 * @return array ['success' => bool, 'message' => string]
 */
function updateProductRichText($conn, $productId, $data)
{
    $productId = (int)$productId;
    $name = trim($data['product_name'] ?? '');
    $catId = (int)($data['category_id'] ?? 0);
    $price = (float)($data['price'] ?? 0);
    $oldPrice = !empty($data['old_price']) ? (float)$data['old_price'] : "NULL";
    $quantity = isset($data['quantity']) ? max(0, (int)$data['quantity']) : 20;
    $image = trim($data['image'] ?? '');
    $summary = trim($data['summary_spec'] ?? '');

    // Xử lý làm sạch chuỗi HTML từ Rich Text Box
    $fullSpecRaw = $data['full_spec'] ?? '';
    $fullSpecClean = sanitizeRichTextHtml($fullSpecRaw);

    if (empty($name)) {
        return ['success' => false, 'message' => 'Tên sản phẩm laptop không được để trống!'];
    }
    if ($catId <= 0) {
        return ['success' => false, 'message' => 'Vui lòng chọn danh mục hãng sản xuất!'];
    }
    if ($price <= 0) {
        return ['success' => false, 'message' => 'Giá bán phải lớn hơn 0!'];
    }

    $escapedName = mysqli_real_escape_string($conn, $name);
    $escapedSummary = mysqli_real_escape_string($conn, $summary);
    $escapedFullSpec = mysqli_real_escape_string($conn, $fullSpecClean);
    $oldPriceSql = ($oldPrice === "NULL") ? "NULL" : "'{$oldPrice}'";

    $imageUpdateSql = "";
    if (!empty($image)) {
        $escapedImage = mysqli_real_escape_string($conn, $image);
        $imageUpdateSql = ", image = '{$escapedImage}'";
    }

    $sql = "UPDATE products 
            SET category_id = {$catId},
                product_name = '{$escapedName}',
                price = {$price},
                old_price = {$oldPriceSql},
                quantity = {$quantity},
                summary_spec = '{$escapedSummary}',
                full_spec = '{$escapedFullSpec}'
                {$imageUpdateSql}
            WHERE product_id = {$productId}";

    if (mysqli_query($conn, $sql)) {
        return ['success' => true, 'message' => 'Cập nhật Laptop với định dạng Rich Text thành công!'];
    }

    return ['success' => false, 'message' => 'Lỗi cập nhật sản phẩm: ' . mysqli_error($conn)];
}

/**
 * Xóa sản phẩm laptop
 * @param mysqli $conn
 * @param int $productId
 * @return array
 */
function deleteProduct($conn, $productId)
{
    $productId = (int)$productId;
    $sql = "DELETE FROM products WHERE product_id = {$productId}";
    if (mysqli_query($conn, $sql)) {
        return ['success' => true, 'message' => 'Đã xóa sản phẩm laptop thành công!'];
    }
    return ['success' => false, 'message' => 'Lỗi xóa sản phẩm: ' . mysqli_error($conn)];
}

/**
 * Phân trang Pixel
 */
function renderPagination($currentPage, $totalPages, $baseUrl)
{
    if ($totalPages <= 1) return;
    $separator = (strpos($baseUrl, '?') !== false) ? '&' : '?';
?>
    <div class="pixel-pagination">
        <?php if ($currentPage > 1): ?>
            <a href="<?= $baseUrl . $separator . 'p=1' ?>" class="page-link page-first" title="Trang đầu">««</a>
            <a href="<?= $baseUrl . $separator . 'p=' . ($currentPage - 1) ?>" class="page-link page-prev" title="Trang trước">«</a>
        <?php endif; ?>

        <?php
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        for ($i = $start; $i <= $end; $i++):
        ?>
            <a href="<?= $baseUrl . $separator . 'p=' . $i ?>" class="page-link <?= ($i === $currentPage) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="<?= $baseUrl . $separator . 'p=' . ($currentPage + 1) ?>" class="page-link page-next" title="Trang sau">»</a>
            <a href="<?= $baseUrl . $separator . 'p=' . $totalPages ?>" class="page-link page-last" title="Trang cuối">»»</a>
        <?php endif; ?>
    </div>
<?php
}
?>