<?php
/**
 * Bài 15: Thư viện Quản lý Giỏ Hàng (Shopping Cart Operations via PHP Session)
 */

/**
 * Khởi tạo Session an toàn
 */
function startCartSession()
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }
}

/**
 * Khởi tạo mảng giỏ hàng trong Session
 */
function initCart()
{
    startCartSession();
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

/**
 * Thêm sản phẩm vào giỏ hàng
 * @param array $product Thông tin sản phẩm từ CSDL
 * @param int $quantity Số lượng muốn thêm (mặc định 1)
 * @return bool
 */
function addToCart($product, $quantity = 1)
{
    initCart();
    $productId = (int)($product['product_id'] ?? 0);
    if ($productId <= 0) return false;

    $quantity = max(1, (int)$quantity);

    if (isset($_SESSION['cart'][$productId])) {
        // Nếu đã có trong giỏ hàng -> cộng dồn số lượng
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Thêm mới sản phẩm vào giỏ
        $_SESSION['cart'][$productId] = [
            'product_id'   => $productId,
            'product_name' => $product['product_name'] ?? 'Laptop',
            'price'        => (float)($product['price'] ?? 0),
            'old_price'    => !empty($product['old_price']) ? (float)$product['old_price'] : null,
            'image'        => $product['image'] ?? 'laptop_default.png',
            'category_name'=> $product['category_name'] ?? '',
            'summary_spec' => $product['summary_spec'] ?? '',
            'quantity'     => $quantity
        ];
    }
    return true;
}

/**
 * Cập nhật số lượng của một sản phẩm trong giỏ hàng
 * @param int $productId
 * @param int $quantity
 */
function updateCartQuantity($productId, $quantity)
{
    initCart();
    $productId = (int)$productId;
    $quantity = (int)$quantity;

    if (isset($_SESSION['cart'][$productId])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId]['quantity'] = min(99, $quantity);
        }
    }
}

/**
 * Xóa một sản phẩm khỏi giỏ hàng
 * @param int $productId
 */
function removeFromCart($productId)
{
    initCart();
    $productId = (int)$productId;
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

/**
 * Làm rỗng giỏ hàng
 */
function clearCart()
{
    initCart();
    $_SESSION['cart'] = [];
}

/**
 * Lấy danh sách sản phẩm trong giỏ hàng
 * @return array
 */
function getCartItems()
{
    initCart();
    return $_SESSION['cart'] ?? [];
}

/**
 * Tính tổng số lượng món hàng trong giỏ
 * @return int
 */
function getCartTotalCount()
{
    initCart();
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += (int)($item['quantity'] ?? 0);
        }
    }
    return $total;
}

/**
 * Tính tổng tiền của giỏ hàng (VNĐ)
 * @return float
 */
function getCartTotalPrice()
{
    initCart();
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $price = (float)($item['price'] ?? 0);
            $qty = (int)($item['quantity'] ?? 0);
            $total += ($price * $qty);
        }
    }
    return $total;
}

/**
 * Lưu thông tin đơn hàng và chi tiết đơn hàng vào CSDL
 * @param mysqli $conn
 * @param array $orderData
 * @param array $cartItems
 * @return array ['success' => bool, 'message' => string, 'order_id' => int]
 */
function saveOrderToDatabase($conn, $orderData, $cartItems)
{
    if (!$conn) {
        return ['success' => false, 'message' => 'Lỗi kết nối cơ sở dữ liệu!', 'order_id' => 0];
    }
    if (empty($cartItems)) {
        return ['success' => false, 'message' => 'Giỏ hàng đang trống, không thể tạo đơn hàng!', 'order_id' => 0];
    }

    $customerName = trim($orderData['customer_name'] ?? '');
    $customerPhone = trim($orderData['customer_phone'] ?? '');
    $customerEmail = trim($orderData['customer_email'] ?? '');
    $customerAddress = trim($orderData['customer_address'] ?? '');
    $orderNotes = trim($orderData['order_notes'] ?? '');
    $paymentMethod = trim($orderData['payment_method'] ?? 'COD');
    $totalAmount = getCartTotalPrice();

    if (empty($customerName) || empty($customerPhone) || empty($customerAddress)) {
        return ['success' => false, 'message' => 'Vui lòng điền đầy đủ Họ tên, Số điện thoại và Địa chỉ giao hàng!', 'order_id' => 0];
    }

    $escapedName = mysqli_real_escape_string($conn, $customerName);
    $escapedPhone = mysqli_real_escape_string($conn, $customerPhone);
    $escapedEmail = mysqli_real_escape_string($conn, $customerEmail);
    $escapedAddress = mysqli_real_escape_string($conn, $customerAddress);
    $escapedNotes = mysqli_real_escape_string($conn, $orderNotes);
    $escapedPayment = mysqli_real_escape_string($conn, $paymentMethod);

    // 1. Thêm vào bảng orders
    $sqlOrder = "INSERT INTO orders (customer_name, customer_phone, customer_email, customer_address, order_notes, payment_method, total_amount, status) 
                 VALUES ('{$escapedName}', '{$escapedPhone}', '{$escapedEmail}', '{$escapedAddress}', '{$escapedNotes}', '{$escapedPayment}', {$totalAmount}, 'confirmed')";

    if (!mysqli_query($conn, $sqlOrder)) {
        return ['success' => false, 'message' => 'Lỗi khi lưu đơn hàng: ' . mysqli_error($conn), 'order_id' => 0];
    }

    $orderId = mysqli_insert_id($conn);

    // 2. Thêm vào bảng order_details
    foreach ($cartItems as $item) {
        $pId = (int)$item['product_id'];
        $pName = mysqli_real_escape_string($conn, $item['product_name']);
        $pPrice = (float)$item['price'];
        $pQty = (int)$item['quantity'];
        $pSubtotal = $pPrice * $pQty;

        $sqlDetail = "INSERT INTO order_details (order_id, product_id, product_name, price, quantity, subtotal) 
                      VALUES ({$orderId}, {$pId}, '{$pName}', {$pPrice}, {$pQty}, {$pSubtotal})";
        mysqli_query($conn, $sqlDetail);
    }

    // 3. Xóa sạch giỏ hàng sau khi đặt thành công
    clearCart();

    return [
        'success'  => true,
        'message'  => 'Đặt hàng thành công! Mã đơn hàng của bạn là #' . $orderId,
        'order_id' => $orderId
    ];
}
