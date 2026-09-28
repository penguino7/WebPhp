<?php

/**
 * Thư viện các hàm trợ giúp và truy vấn CSDL cho Website Bán Laptop (Bài 13)
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
    if ($priceNum <= 0) {
        return "Liên hệ";
    }
    return number_format($priceNum, 0, ',', '.') . " ₫";
}

/**
 * Lấy tất cả danh mục hãng laptop kèm số lượng sản phẩm
 * @param mysqli $conn
 * @return array
 */
function getAllCategories($conn)
{
    if (!$conn) {
        return [];
    }
    $sql = "SELECT c.category_id, c.category_name, COUNT(p.product_id) as total_products 
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
 * Lấy thông tin 1 danh mục theo ID
 * @param mysqli $conn
 * @param int $catId
 * @return array|null
 */
function getCategoryById($conn, $catId)
{
    if (!$conn) {
        return null;
    }
    $catId = (int)$catId;
    $sql = "SELECT * FROM categories WHERE category_id = {$catId} LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row;
    }
    return null;
}

/**
 * Lấy danh sách sản phẩm theo hãng (Category)
 * @param mysqli $conn
 * @param int $catId
 * @param int $limit
 * @return array
 */
function getProductsByCategory($conn, $catId, $limit = 0)
{
    if (!$conn) {
        return [];
    }
    $catId = (int)$catId;
    $sql = "SELECT * FROM products WHERE category_id = {$catId} ORDER BY created_at DESC, product_id DESC";
    if ($limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $result = mysqli_query($conn, $sql);
    $products = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        mysqli_free_result($result);
    }
    return $products;
}

/**
 * Lấy sản phẩm mới nhất của mỗi hãng laptop (dùng cho trang Home)
 * @param mysqli $conn
 * @param int $limitPerCategory
 * @return array Mảng dạng: [ ['category' => $cat, 'products' => [$p1, $p2]], ... ]
 */
function getHomeSectionsWithProducts($conn, $limitPerCategory = 2)
{
    $categories = getAllCategories($conn);
    $sections = [];

    foreach ($categories as $cat) {
        $products = getProductsByCategory($conn, (int)$cat['category_id'], $limitPerCategory);
        if (!empty($products)) {
            $sections[] = [
                'category' => $cat,
                'products' => $products
            ];
        }
    }
    return $sections;
}

/**
 * Lấy chi tiết 1 sản phẩm laptop theo ID
 * @param mysqli $conn
 * @param int $productId
 * @return array|null
 */
function getProductById($conn, $productId)
{
    if (!$conn) {
        return null;
    }
    $productId = (int)$productId;
    $sql = "SELECT p.*, c.category_name 
            FROM products p 
            INNER JOIN categories c ON p.category_id = c.category_id 
            WHERE p.product_id = {$productId} 
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row;
    }
    return null;
}

/**
 * Tìm kiếm sản phẩm theo từ khóa và danh mục
 * @param mysqli $conn
 * @param string $keyword
 * @param int $catId
 * @return array
 */
function searchProducts($conn, $keyword = '', $catId = 0)
{
    if (!$conn) {
        return [];
    }
    $keyword = trim($keyword);
    $escapedKeyword = mysqli_real_escape_string($conn, $keyword);
    $catId = (int)$catId;

    $sql = "SELECT p.*, c.category_name 
            FROM products p 
            INNER JOIN categories c ON p.category_id = c.category_id 
            WHERE 1=1 ";

    if (!empty($keyword)) {
        $sql .= " AND (p.product_name LIKE '%{$escapedKeyword}%' OR p.summary_spec LIKE '%{$escapedKeyword}%') ";
    }

    if ($catId > 0) {
        $sql .= " AND p.category_id = {$catId} ";
    }

    $sql .= " ORDER BY p.created_at DESC, p.product_id DESC";

    $result = mysqli_query($conn, $sql);
    $products = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        mysqli_free_result($result);
    }
    return $products;
}

/**
 * Lấy danh sách sản phẩm cùng hãng liên quan
 * @param mysqli $conn
 * @param int $catId
 * @param int $excludeProductId
 * @param int $limit
 * @return array
 */
function getRelatedProducts($conn, $catId, $excludeProductId, $limit = 4)
{
    if (!$conn) {
        return [];
    }
    $catId = (int)$catId;
    $excludeProductId = (int)$excludeProductId;
    $sql = "SELECT * FROM products 
            WHERE category_id = {$catId} AND product_id != {$excludeProductId} 
            ORDER BY created_at DESC LIMIT " . (int)$limit;
    $result = mysqli_query($conn, $sql);
    $products = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        mysqli_free_result($result);
    }
    return $products;
}

/**
 * Hiển thị thẻ HTML sản phẩm dạng Card
 * @param array $product
 */
function renderProductCard($product)
{
    $imgSrc = "images/" . htmlspecialchars($product['image'] ?? 'laptop_default.png');
    $detailUrl = "index.php?page=productDetail&id=" . (int)$product['product_id'];
    $priceFormatted = formatPrice($product['price'] ?? 0);
    $oldPriceFormatted = !empty($product['old_price']) ? formatPrice($product['old_price']) : '';
    $discountPercent = 0;
    if (!empty($product['old_price']) && $product['old_price'] > $product['price']) {
        $discountPercent = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100);
    }
?>
    <div class="product-card">
        <?php if ($discountPercent > 0): ?>
            <span class="badge-discount">-<?= $discountPercent ?>%</span>
        <?php endif; ?>
        <div class="product-thumb">
            <a href="<?= $detailUrl ?>">
                <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($product['product_name'] ?? 'Laptop') ?>" onerror="this.onerror=null;this.src='images/laptop_default.png';">
            </a>
        </div>
        <div class="product-info">
            <h3 class="product-title">
                <a href="<?= $detailUrl ?>" title="<?= htmlspecialchars($product['product_name'] ?? '') ?>">
                    <?= htmlspecialchars($product['product_name'] ?? '') ?>
                </a>
            </h3>
            <div class="product-specs">
                <?= htmlspecialchars($product['summary_spec'] ?? '') ?>
            </div>
            <div class="product-price-box">
                <span class="price-current"><?= $priceFormatted ?></span>
                <?php if (!empty($oldPriceFormatted)): ?>
                    <span class="price-old"><?= $oldPriceFormatted ?></span>
                <?php endif; ?>
            </div>
            <div class="product-actions">
                <a href="<?= $detailUrl ?>" class="btn-detail">Xem chi tiết</a>
                <a href="javascript:alert('Đã thêm sản phẩm vào giỏ hàng!');" class="btn-buy" title="Thêm vào giỏ">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    Mua
                </a>
            </div>
        </div>
    </div>
<?php
}
?>