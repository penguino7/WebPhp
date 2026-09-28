<?php

/**
 * Thư viện các hàm CRUD dữ liệu & Tiện ích Quản trị (Admin Helper) - Bài 14
 */

require_once __DIR__ . '/connect.php';

// ==========================================================================
// 1. CÁC HÀM TIỆN ÍCH CHUNG (GENERAL UTILITIES)
// ==========================================================================

/**
 * Định dạng tiền tệ VNĐ
 * @param float|int|string|null $price
 * @return string
 */
function formatPrice($price)
{
    $priceNum = (float)$price;
    if ($priceNum <= 0) {
        return "0 ₫";
    }
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
 * Xử lý Upload file ảnh sản phẩm an toàn
 * @param array $file Mảng $_FILES['image']
 * @param string $uploadDir Đường dẫn thư mục lưu ảnh
 * @return array ['success' => bool, 'fileName' => string, 'message' => string]
 */
function uploadProductImage($file, $uploadDir = '../Bai13/images/')
{
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'fileName' => 'laptop_default.png', 'message' => 'Sử dụng ảnh mặc định'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'fileName' => '', 'message' => 'Lỗi upload file: ' . $file['error']];
    }

    // 1. Kiểm tra dung lượng (Max 5MB)
    $maxSize = 5 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'fileName' => '', 'message' => 'Dung lượng file ảnh vượt quá giới hạn 5MB!'];
    }

    // 2. Kiểm tra đuôi file hợp lệ
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
    if (!in_array($ext, $allowedExts)) {
        return ['success' => false, 'fileName' => '', 'message' => 'Định dạng ảnh không hợp lệ (Chỉ chấp nhận JPG, PNG, WEBP, GIF, SVG)!'];
    }

    // 3. Tạo tên file duy nhất tránh trùng lặp
    $newFileName = 'laptop_' . time() . '_' . rand(1000, 9999) . '.' . $ext;

    // Đảm bảo thư mục đích tồn tại
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $targetPath = rtrim($uploadDir, '/') . '/' . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'fileName' => $newFileName, 'message' => 'Upload ảnh thành công!'];
    }

    return ['success' => false, 'fileName' => '', 'message' => 'Không thể lưu file ảnh vào thư mục máy chủ!'];
}

// ==========================================================================
// 2. CÁC HÀM THỐNG KÊ DASHBOARD
// ==========================================================================

/**
 * Lấy các chỉ số thống kê tổng quan cho Dashboard
 * @param mysqli $conn
 * @return array
 */
function getDashboardStats($conn)
{
    $stats = [
        'total_products'   => 0,
        'total_categories' => 0,
        'total_admins'     => 0,
        'max_price'        => 0,
        'min_price'        => 0,
        'avg_price'        => 0
    ];

    if (!$conn) return $stats;

    // Đếm tổng sản phẩm và giá trị
    $sqlProd = "SELECT COUNT(*) as total, MAX(price) as max_p, MIN(price) as min_p, AVG(price) as avg_p FROM products";
    $resProd = mysqli_query($conn, $sqlProd);
    if ($resProd && $row = mysqli_fetch_assoc($resProd)) {
        $stats['total_products'] = (int)$row['total'];
        $stats['max_price']      = (float)$row['max_p'];
        $stats['min_price']      = (float)$row['min_p'];
        $stats['avg_price']      = (float)$row['avg_p'];
        mysqli_free_result($resProd);
    }

    // Đếm tổng danh mục hãng
    $sqlCat = "SELECT COUNT(*) as total FROM categories";
    $resCat = mysqli_query($conn, $sqlCat);
    if ($resCat && $row = mysqli_fetch_assoc($resCat)) {
        $stats['total_categories'] = (int)$row['total'];
        mysqli_free_result($resCat);
    }

    // Đếm tổng tài khoản admin
    $sqlAdm = "SELECT COUNT(*) as total FROM admins";
    $resAdm = mysqli_query($conn, $sqlAdm);
    if ($resAdm && $row = mysqli_fetch_assoc($resAdm)) {
        $stats['total_admins'] = (int)$row['total'];
        mysqli_free_result($resAdm);
    }

    return $stats;
}

/**
 * Lấy danh sách laptop mới thêm gần đây cho Dashboard
 * @param mysqli $conn
 * @param int $limit
 * @return array
 */
function getRecentProducts($conn, $limit = 5)
{
    if (!$conn) return [];
    $limit = (int)$limit;
    $sql = "SELECT p.*, c.category_name 
            FROM products p 
            INNER JOIN categories c ON p.category_id = c.category_id 
            ORDER BY p.product_id DESC 
            LIMIT {$limit}";
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

// ==========================================================================
// 3. CÁC HÀM CRUD DANH MỤC HÃNG (CATEGORIES)
// ==========================================================================

/**
 * Lấy tất cả danh mục kèm số lượng laptop
 * @param mysqli $conn
 * @return array
 */
function getAllCategoriesWithCount($conn)
{
    if (!$conn) return [];
    $sql = "SELECT c.*, COUNT(p.product_id) as product_count 
            FROM categories c 
            LEFT JOIN products p ON c.category_id = p.category_id 
            GROUP BY c.category_id, c.category_name 
            ORDER BY c.category_id ASC";
    $result = mysqli_query($conn, $sql);
    $categories = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
        mysqli_free_result($result);
    }
    return $categories;
}

/**
 * Lấy chi tiết 1 danh mục theo ID
 * @param mysqli $conn
 * @param int $catId
 * @return array|null
 */
function getCategoryById($conn, $catId)
{
    if (!$conn) return null;
    $catId = (int)$catId;
    $sql = "SELECT * FROM categories WHERE category_id = {$catId} LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row;
    }
    return null;
}

/**
 * Thêm mới Danh mục Hãng Laptop
 * @param mysqli $conn
 * @param array $data ['category_name' => ..., 'description' => ...]
 * @return array ['success' => bool, 'message' => string]
 */
function insertCategory($conn, $data)
{
    $catName = trim($data['category_name'] ?? '');
    $desc = trim($data['description'] ?? '');

    if (empty($catName)) {
        return ['success' => false, 'message' => 'Tên danh mục hãng không được để trống!'];
    }

    $escapedName = mysqli_real_escape_string($conn, $catName);
    $escapedDesc = mysqli_real_escape_string($conn, $desc);

    // Kiểm tra trùng tên
    $chkSql = "SELECT category_id FROM categories WHERE category_name = '{$escapedName}' LIMIT 1";
    $chkRes = mysqli_query($conn, $chkSql);
    if ($chkRes && mysqli_num_rows($chkRes) > 0) {
        mysqli_free_result($chkRes);
        return ['success' => false, 'message' => "Tên hãng '{$catName}' đã tồn tại trong hệ thống!"];
    }

    $sql = "INSERT INTO categories (category_name, description) VALUES ('{$escapedName}', '{$escapedDesc}')";
    if (mysqli_query($conn, $sql)) {
        return ['success' => true, 'message' => 'Thêm mới danh mục hãng thành công!'];
    }

    return ['success' => false, 'message' => 'Lỗi thêm danh mục: ' . mysqli_error($conn)];
}

/**
 * Cập nhật Danh mục Hãng Laptop
 * @param mysqli $conn
 * @param int $catId
 * @param array $data
 * @return array ['success' => bool, 'message' => string]
 */
function updateCategory($conn, $catId, $data)
{
    $catId = (int)$catId;
    $catName = trim($data['category_name'] ?? '');
    $desc = trim($data['description'] ?? '');

    if (empty($catName)) {
        return ['success' => false, 'message' => 'Tên danh mục hãng không được để trống!'];
    }

    $escapedName = mysqli_real_escape_string($conn, $catName);
    $escapedDesc = mysqli_real_escape_string($conn, $desc);

    // Kiểm tra trùng tên với hãng khác
    $chkSql = "SELECT category_id FROM categories WHERE category_name = '{$escapedName}' AND category_id != {$catId} LIMIT 1";
    $chkRes = mysqli_query($conn, $chkSql);
    if ($chkRes && mysqli_num_rows($chkRes) > 0) {
        mysqli_free_result($chkRes);
        return ['success' => false, 'message' => "Tên hãng '{$catName}' đã bị trùng với danh mục khác!"];
    }

    $sql = "UPDATE categories SET category_name = '{$escapedName}', description = '{$escapedDesc}' WHERE category_id = {$catId}";
    if (mysqli_query($conn, $sql)) {
        return ['success' => true, 'message' => 'Cập nhật danh mục hãng thành công!'];
    }

    return ['success' => false, 'message' => 'Lỗi cập nhật danh mục: ' . mysqli_error($conn)];
}

/**
 * Xóa Danh mục Hãng Laptop (Kiểm tra an toàn)
 * @param mysqli $conn
 * @param int $catId
 * @return array ['success' => bool, 'message' => string]
 */
function deleteCategory($conn, $catId)
{
    $catId = (int)$catId;

    // Kiểm tra xem danh mục có sản phẩm laptop nào không
    $chkSql = "SELECT COUNT(*) as count FROM products WHERE category_id = {$catId}";
    $chkRes = mysqli_query($conn, $chkSql);
    if ($chkRes && $row = mysqli_fetch_assoc($chkRes)) {
        $count = (int)$row['count'];
        mysqli_free_result($chkRes);
        if ($count > 0) {
            return [
                'success' => false,
                'message' => "Không thể xóa hãng này vì hiện đang có {$count} sản phẩm laptop thuộc danh mục!"
            ];
        }
    }

    $sql = "DELETE FROM categories WHERE category_id = {$catId}";
    if (mysqli_query($conn, $sql)) {
        return ['success' => true, 'message' => 'Đã xóa danh mục hãng thành công!'];
    }

    return ['success' => false, 'message' => 'Lỗi xóa danh mục: ' . mysqli_error($conn)];
}

// ==========================================================================
// 4. CÁC HÀM CRUD SẢN PHẨM LAPTOP (PRODUCTS)
// ==========================================================================

/**
 * Lấy danh sách sản phẩm phân trang + tìm kiếm + lọc theo hãng cho Admin
 * @param mysqli $conn
 * @param string $keyword
 * @param int $catId
 * @param int $page
 * @param int $limit
 * @return array ['products' => array, 'total_records' => int, 'total_pages' => int, 'current_page' => int]
 */
function getAdminProducts($conn, $keyword = '', $catId = 0, $page = 1, $limit = 8)
{
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

    // 2. Lấy danh sách dữ liệu trang hiện tại
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
 * Thêm mới 1 sản phẩm laptop
 * @param mysqli $conn
 * @param array $data
 * @return array ['success' => bool, 'message' => string]
 */
function insertProduct($conn, $data)
{
    $name = trim($data['product_name'] ?? '');
    $catId = (int)($data['category_id'] ?? 0);
    $price = (float)($data['price'] ?? 0);
    $oldPrice = !empty($data['old_price']) ? (float)$data['old_price'] : "NULL";
    $image = trim($data['image'] ?? 'laptop_default.png');
    $summary = trim($data['summary_spec'] ?? '');
    $fullSpec = trim($data['full_spec'] ?? '');

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
    $escapedImage = mysqli_real_escape_string($conn, $image);
    $escapedSummary = mysqli_real_escape_string($conn, $summary);
    $escapedFullSpec = mysqli_real_escape_string($conn, $fullSpec);
    $oldPriceSql = ($oldPrice === "NULL") ? "NULL" : "'{$oldPrice}'";

    $sql = "INSERT INTO products (category_id, product_name, price, old_price, image, summary_spec, full_spec) 
            VALUES ({$catId}, '{$escapedName}', {$price}, {$oldPriceSql}, '{$escapedImage}', '{$escapedSummary}', '{$escapedFullSpec}')";

    if (mysqli_query($conn, $sql)) {
        return ['success' => true, 'message' => 'Thêm mới sản phẩm laptop thành công!'];
    }

    return ['success' => false, 'message' => 'Lỗi thêm sản phẩm: ' . mysqli_error($conn)];
}

/**
 * Cập nhật 1 sản phẩm laptop
 * @param mysqli $conn
 * @param int $productId
 * @param array $data
 * @return array ['success' => bool, 'message' => string]
 */
function updateProduct($conn, $productId, $data)
{
    $productId = (int)$productId;
    $name = trim($data['product_name'] ?? '');
    $catId = (int)($data['category_id'] ?? 0);
    $price = (float)($data['price'] ?? 0);
    $oldPrice = !empty($data['old_price']) ? (float)$data['old_price'] : "NULL";
    $image = trim($data['image'] ?? '');
    $summary = trim($data['summary_spec'] ?? '');
    $fullSpec = trim($data['full_spec'] ?? '');

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
    $escapedFullSpec = mysqli_real_escape_string($conn, $fullSpec);
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
                summary_spec = '{$escapedSummary}',
                full_spec = '{$escapedFullSpec}'
                {$imageUpdateSql}
            WHERE product_id = {$productId}";

    if (mysqli_query($conn, $sql)) {
        return ['success' => true, 'message' => 'Cập nhật sản phẩm laptop thành công!'];
    }

    return ['success' => false, 'message' => 'Lỗi cập nhật sản phẩm: ' . mysqli_error($conn)];
}

/**
 * Xóa 1 sản phẩm laptop
 * @param mysqli $conn
 * @param int $productId
 * @return array ['success' => bool, 'message' => string]
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

// ==========================================================================
// 5. CÁC HÀM RENDER GIAO DIỆN (UI RENDERERS)
// ==========================================================================

/**
 * Render thanh phân trang Pixel phong cách Arcade
 * @param int $currentPage
 * @param int $totalPages
 * @param string $baseUrl URL gốc kèm query params (vd: index.php?page=products_list&cat_id=1)
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