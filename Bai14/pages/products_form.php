<?php

/**
 * Form Thêm Mới / Chỉnh Sửa Laptop (Products Form) - Bài 14
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/auth.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Render Form Thêm / Sửa Laptop
 * @param mysqli $conn
 */
function renderProductFormPage($conn)
{
    $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $isEdit = ($productId > 0);
    $product = null;
    $errorMsg = '';
    $categories = getAllCategoriesWithCount($conn);

    if ($isEdit) {
        $product = getProductById($conn, $productId);
        if (!$product) {
            renderAlert('danger', 'Sản phẩm laptop cần sửa không tồn tại!');
            echo "<a href='index.php?page=products_list' class='btn-pixel btn-primary-px'>⬅ Quay lại danh sách</a>";
            return;
        }
    }

    $nameVal     = $product['product_name'] ?? '';
    $catIdVal    = (int)($product['category_id'] ?? 0);
    $priceVal    = $product['price'] ?? '';
    $oldPriceVal = $product['old_price'] ?? '';
    $qtyVal      = isset($product['quantity']) ? (int)$product['quantity'] : 20;
    $imageVal    = $product['image'] ?? 'laptop_default.png';
    $summaryVal  = $product['summary_spec'] ?? '';
    $fullSpecVal = $product['full_spec'] ?? '';

    // Xử lý Submit Form
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
        $nameVal     = trim($_POST['product_name'] ?? '');
        $catIdVal    = (int)($_POST['category_id'] ?? 0);
        $priceVal    = trim($_POST['price'] ?? '');
        $oldPriceVal = trim($_POST['old_price'] ?? '');
        $qtyVal      = isset($_POST['quantity']) ? max(0, (int)$_POST['quantity']) : 20;
        $summaryVal  = trim($_POST['summary_spec'] ?? '');
        $fullSpecVal = trim($_POST['full_spec'] ?? '');

        // Xử lý Upload ảnh nếu có
        $uploadedImageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upResult = uploadProductImage($_FILES['image'], '../Bai13/images/');
            if ($upResult['success']) {
                $uploadedImageName = $upResult['fileName'];
            } else {
                $errorMsg = $upResult['message'];
            }
        }

        if (empty($errorMsg)) {
            $data = [
                'product_name' => $nameVal,
                'category_id'  => $catIdVal,
                'price'        => $priceVal,
                'old_price'    => !empty($oldPriceVal) ? $oldPriceVal : null,
                'quantity'     => $qtyVal,
                'summary_spec' => $summaryVal,
                'full_spec'    => $fullSpecVal
            ];

            if ($isEdit) {
                if (!empty($uploadedImageName)) {
                    $data['image'] = $uploadedImageName;
                }
                $res = updateProduct($conn, $productId, $data);
            } else {
                $data['image'] = !empty($uploadedImageName) ? $uploadedImageName : 'laptop_default.png';
                $res = insertProduct($conn, $data);
            }

            if ($res['success']) {
                $encodedMsg = urlencode($res['message']);
                header("Location: index.php?page=products_list&msg={$encodedMsg}&msg_type=success");
                exit();
            } else {
                $errorMsg = $res['message'];
            }
        }
    }

    $formTitle = $isEdit ? "✏️ CHỈNH SỬA LAPTOP #{$productId}" : "⚡ THÊM MỚI SẢN PHẨM LAPTOP";
    $btnLabel  = $isEdit ? "💾 CẬP NHẬT THÔNG TIN" : "⚡ LƯU LAPTOP MỚI";
?>
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">
                <span>💻</span> <?= $formTitle ?>
            </h2>
            <div class="header-tools">
                <a href="index.php?page=products_list" class="btn-pixel btn-dark-px">
                    ⬅ Quay lại danh sách
                </a>
            </div>
        </div>

        <?php if (!empty($errorMsg)): ?>
            <?php renderAlert('danger', $errorMsg); ?>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="pixel-form">
            <!-- Tên Laptop -->
            <div class="form-group">
                <label for="product_name">
                    <span class="label-icon">💻</span> TÊN LAPTOP: <span style="color:var(--px-pink)">*</span>
                </label>
                <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($nameVal) ?>" placeholder="vd: Dell XPS 13 Plus 9320 (i7 1360P/32GB/1TB/OLED)..." required>
            </div>

            <!-- Hãng Sản Xuất & Số Lượng Tồn Kho -->
            <div class="form-row">
                <div class="form-group">
                    <label for="category_id">
                        <span class="label-icon">📁</span> HÃNG SẢN XUẤT: <span style="color:var(--px-pink)">*</span>
                    </label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Chọn Hãng Laptop --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['category_id'] ?>" <?= ($catIdVal === (int)$c['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">
                        <span class="label-icon">📦</span> SỐ LƯỢNG TỒN KHO: <span style="color:var(--px-pink)">*</span>
                    </label>
                    <input type="number" id="quantity" name="quantity" value="<?= htmlspecialchars($qtyVal) ?>" placeholder="vd: 20" min="0" required>
                    <div class="form-hint">Số máy hiện có sẵn trong kho để bán.</div>
                </div>
            </div>

            <!-- Giá Bán & Giá Niêm Yết -->
            <div class="form-row">
                <div class="form-group">
                    <label for="price">
                        <span class="label-icon">💰</span> GIÁ BÁN (VNĐ): <span style="color:var(--px-pink)">*</span>
                    </label>
                    <input type="number" id="price" name="price" value="<?= htmlspecialchars($priceVal) ?>" placeholder="vd: 14990000" min="0" step="10000" required>
                </div>

                <div class="form-group">
                    <label for="old_price">
                        <span class="label-icon">🏷️</span> GIÁ NIÊM YẾT / GIÁ CŨ (VNĐ):
                    </label>
                    <input type="number" id="old_price" name="old_price" value="<?= htmlspecialchars($oldPriceVal) ?>" placeholder="vd: 16990000 (Để trống nếu không giảm giá)" min="0" step="10000">
                </div>
            </div>

            <!-- Upload Ảnh -->
            <div class="form-group">
                <label for="image">
                    <span class="label-icon">🖼️</span> ẢNH ĐẠI DIỆN SẢN PHẨM:
                </label>
                <input type="file" id="image" name="image" accept="image/*">
                <div class="form-hint">Hỗ trợ JPG, PNG, WEBP, SVG (Tối đa 5MB).</div>

                <?php if ($isEdit && !empty($imageVal)): ?>
                    <div style="margin-top:8px; display:flex; align-items:center; gap:10px;">
                        <span style="font-size:12px; color:var(--px-text-muted);">Ảnh hiện tại:</span>
                        <img src="../Bai13/images/<?= htmlspecialchars($imageVal) ?>" alt="Ảnh hiện tại" style="width:50px; height:40px; object-fit:contain; border:2px solid var(--px-border); background:var(--px-input-bg);" onerror="this.onerror=null; this.src='../Bai13/images/laptop_default.png';">
                        <code style="font-size:12px; color:var(--px-green);"><?= htmlspecialchars($imageVal) ?></code>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Cấu Hình Tóm Tắt (Summary Spec) -->
            <div class="form-group">
                <label for="summary_spec">
                    <span class="label-icon">⚡</span> CẤU HÌNH TÓM TẮT:
                </label>
                <textarea id="summary_spec" name="summary_spec" rows="3" placeholder="vd: CPU: Intel Core i5-1235U | RAM: 16GB DDR4 | SSD: 512GB NVMe | Màn hình: 15.6 FHD 120Hz | VGA: Intel Iris Xe"><?= htmlspecialchars($summaryVal) ?></textarea>
                <div class="form-hint">Hiển thị trên thẻ sản phẩm ở Trang chủ và Danh sách tìm kiếm.</div>
            </div>

            <!-- Cấu Hình Chi Tiết (Full HTML Spec) -->
            <div class="form-group">
                <label for="full_spec">
                    <span class="label-icon">📋</span> THÔNG SỐ KỸ THUẬT CHI TIẾT (HTML):
                </label>
                <textarea id="full_spec" name="full_spec" rows="8" placeholder="Nhập bảng hoặc danh sách thông số kỹ thuật chi tiết... (vd: <ul><li><strong>CPU:</strong> Intel Core i7...</li></ul>)"><?= htmlspecialchars($fullSpecVal) ?></textarea>
                <div class="form-hint">Hỗ trợ định dạng HTML (thẻ &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;, &lt;table&gt;...).</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-pixel btn-success-px">
                    <?= $btnLabel ?>
                </button>
                <a href="index.php?page=products_list" class="btn-pixel btn-dark-px">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
<?php
}

renderProductFormPage($conn);
?>