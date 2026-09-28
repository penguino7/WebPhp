<?php

/**
 * Bài 16: Form Thêm / Sửa Laptop tích hợp Rich Text Box (WYSIWYG CKEditor)
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/auth.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = ($productId > 0);

$product = null;
if ($isEdit) {
    $product = getProductById($conn, $productId);
    if (!$product) {
        renderAlert('danger', 'Không tìm thấy sản phẩm laptop có ID: ' . $productId);
        echo '<div style="margin-top: 15px;"><a href="index.php?page=products_list" class="pixel-btn pixel-btn-primary">« Quay lại danh sách</a></div>';
        return;
    }
}

$categories = getAllCategories($conn);
$message = '';
$messageType = '';

// Xử lý submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'product_name' => $_POST['product_name'] ?? '',
        'category_id'  => (int)($_POST['category_id'] ?? 0),
        'price'        => (float)($_POST['price'] ?? 0),
        'old_price'    => !empty($_POST['old_price']) ? (float)$_POST['old_price'] : null,
        'image'        => trim($_POST['image'] ?? 'laptop_default.png'),
        'summary_spec' => trim($_POST['summary_spec'] ?? ''),
        'full_spec'    => $_POST['full_spec'] ?? '' // Nội dung HTML từ Rich Text Box
    ];

    if ($isEdit) {
        $res = updateProductRichText($conn, $productId, $data);
        $message = $res['message'];
        $messageType = $res['success'] ? 'success' : 'danger';
        if ($res['success']) {
            $product = getProductById($conn, $productId);
        }
    } else {
        $res = insertProductRichText($conn, $data);
        $message = $res['message'];
        $messageType = $res['success'] ? 'success' : 'danger';
        if ($res['success']) {
            $productId = $res['insert_id'];
            $isEdit = true;
            $product = getProductById($conn, $productId);
        }
    }
}

// Giá trị ban đầu cho form (ưu tiên $_POST để giữ dữ liệu vừa nhập nếu submit)
$valName = $_POST['product_name'] ?? ($product['product_name'] ?? '');
$valCat = isset($_POST['category_id']) ? (int)$_POST['category_id'] : ($product['category_id'] ?? 0);
$valPrice = $_POST['price'] ?? ($product['price'] ?? '');
$valOldPrice = $_POST['old_price'] ?? ($product['old_price'] ?? '');
$valImage = $_POST['image'] ?? ($product['image'] ?? 'laptop_default.png');
$valSummary = $_POST['summary_spec'] ?? ($product['summary_spec'] ?? '');
$valFullSpec = $_POST['full_spec'] ?? ($product['full_spec'] ?? '');

// Mẫu HTML mẫu mặc định nếu đang tạo mới và chưa có nội dung
if (!$isEdit && empty($valFullSpec)) {
    $valFullSpec = '<h3>✨ Đặc Điểm Nổi Bật</h3>
<p>Laptop thế hệ mới được trang bị vi xử lý hiệu năng cao, màn hình sắc nét và thời lượng pin ấn tượng.</p>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr style="background-color:#4c00b0; color:#fff;">
            <th style="width:30%;">Thành Phần</th>
            <th>Thông Số Kỹ Thuật</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>CPU</strong></td>
            <td>Intel Core i7 / AMD Ryzen 7 thế hệ mới nhất</td>
        </tr>
        <tr>
            <td><strong>RAM</strong></td>
            <td>16GB DDR5 5600MHz (Nâng cấp tối đa 64GB)</td>
        </tr>
        <tr>
            <td><strong>Ổ Cứng</strong></td>
            <td>512GB SSD NVMe PCIe Gen 4x4</td>
        </tr>
        <tr>
            <td><strong>Card Đồ Họa</strong></td>
            <td>NVIDIA GeForce RTX 4060 8GB GDDR6</td>
        </tr>
        <tr>
            <td><strong>Màn Hình</strong></td>
            <td>15.6 inch 2.5K QHD (2560x1440), 165Hz, 100% sRGB</td>
        </tr>
    </tbody>
</table>';
}
?>

<div class="pixel-card">
    <div class="card-header-bar flex-between">
        <div>
            <h2 class="card-title">
                <?= $isEdit ? '✏️ CẬP NHẬT LAPTOP (RICH TEXT BOX)' : '➕ THÊM MỚI LAPTOP (RICH TEXT BOX)' ?>
            </h2>
            <p class="card-subtitle">
                Tích hợp CKEditor WYSIWYG - Soạn thảo HTML trực quan, hỗ trợ bảng thông số, định dạng chữ và chèn hình ảnh
            </p>
        </div>
        <div class="action-btn-group">
            <?php if ($isEdit): ?>
                <a href="index.php?page=product_preview&id=<?= $productId ?>" class="pixel-btn pixel-btn-info" target="_blank">
                    👁️ XEM TRƯỚC HTML
                </a>
            <?php endif; ?>
            <a href="index.php?page=products_list" class="pixel-btn pixel-btn-secondary">
                « DANH SÁCH
            </a>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <?php renderAlert($messageType, $message); ?>
    <?php endif; ?>

    <form method="POST" action="" class="pixel-form" id="richTextProductForm">

        <!-- Hàng 1: Tên & Danh mục hãng -->
        <div class="form-grid-2">
            <div class="form-group">
                <label for="product_name">TÊN SẢN PHẨM LAPTOP <span style="color: var(--color-danger);">*</span></label>
                <input type="text" id="product_name" name="product_name" class="pixel-input"
                    placeholder="VD: ASUS TUF Gaming A15 FA507NV"
                    value="<?= htmlspecialchars($valName) ?>" required>
            </div>

            <div class="form-group">
                <label for="category_id">DANH MỤC HÃNG SẢN XUẤT <span style="color: var(--color-danger);">*</span></label>
                <select id="category_id" name="category_id" class="pixel-input" required>
                    <option value="">-- Chọn thương hiệu Laptop --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= ($valCat == $cat['category_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Hàng 2: Giá & Giá cũ & Ảnh -->
        <div class="form-grid-3">
            <div class="form-group">
                <label for="price">GIÁ BÁN HIỆN TẠI (VNĐ) <span style="color: var(--color-danger);">*</span></label>
                <input type="number" step="10000" id="price" name="price" class="pixel-input"
                    placeholder="VD: 25990000"
                    value="<?= htmlspecialchars($valPrice) ?>" required>
            </div>

            <div class="form-group">
                <label for="old_price">GIÁ GỐC / GIÁ CŨ (VNĐ)</label>
                <input type="number" step="10000" id="old_price" name="old_price" class="pixel-input"
                    placeholder="VD: 28990000 (Để trống nếu không có)"
                    value="<?= htmlspecialchars($valOldPrice) ?>">
            </div>

            <div class="form-group">
                <label for="image">TÊN FILE ẢNH MINH HỌA</label>
                <input type="text" id="image" name="image" list="laptopImagesList" class="pixel-input"
                    placeholder="VD: asus_tuf_a15.png"
                    value="<?= htmlspecialchars($valImage) ?>">
                <datalist id="laptopImagesList">
                    <option value="acer_nitro_v.png">Acer Nitro V</option>
                    <option value="acer_swift_go.png">Acer Swift Go</option>
                    <option value="asus_tuf_a15.png">ASUS TUF Gaming A15</option>
                    <option value="asus_zenbook_14.png">ASUS Zenbook 14</option>
                    <option value="dell_g15.png">Dell G15 Gaming</option>
                    <option value="dell_inspiron_3520.png">Dell Inspiron 3520</option>
                    <option value="dell_xps_13.png">Dell XPS 13</option>
                    <option value="hp_envy_x360.png">HP Envy x360</option>
                    <option value="hp_pavilion_14.png">HP Pavilion 14</option>
                    <option value="lenovo_legion_5.png">Lenovo Legion 5</option>
                    <option value="lenovo_thinkpad_x1.png">Lenovo ThinkPad X1 Carbon</option>
                    <option value="macbook_air_m3.png">MacBook Air M3</option>
                    <option value="macbook_pro_14.png">MacBook Pro 14 M3</option>
                    <option value="samsung_galaxy_book4.png">Samsung Galaxy Book4</option>
                    <option value="sony_vaio_fe14.png">Sony VAIO FE14</option>
                    <option value="sony_vaio_sx14.png">Sony VAIO SX14</option>
                    <option value="laptop_default.png">Mặc định</option>
                </datalist>
            </div>
        </div>

        <!-- Hàng 3: Tóm tắt thông số (Plain Text) -->
        <div class="form-group">
            <label for="summary_spec">CẤU HÌNH TÓM TẮT (Hiển thị thẻ Card / Danh sách nhanh)</label>
            <textarea id="summary_spec" name="summary_spec" class="pixel-input" rows="2"
                placeholder="VD: Ryzen 7 7735HS / RTX 4060 8GB / 16GB RAM / 512GB SSD / 15.6' 144Hz"><?= htmlspecialchars($valSummary) ?></textarea>
        </div>

        <!-- Hàng 4: KHU VỰC SOẠN THẢO RICH TEXT BOX (WYSIWYG CKEDITOR) -->
        <div class="form-group" style="margin-top: 15px;">
            <div class="flex-between" style="margin-bottom: 8px;">
                <label for="full_spec" style="font-size: 1rem; color: var(--color-accent); font-weight: 700;">
                    📝 THÔNG SỐ KỸ THUẬT &amp; MÔ TẢ CHI TIẾT (RICH TEXT BOX / HTML)
                </label>

                <!-- Thanh công cụ chèn nhanh mẫu template -->
                <div class="template-quick-buttons" style="display: flex; gap: 8px;">
                    <button type="button" class="pixel-btn pixel-btn-info btn-sm" onclick="insertGamingTemplate()">
                        📋 Chèn Mẫu Gaming
                    </button>
                    <button type="button" class="pixel-btn pixel-btn-warning btn-sm" onclick="insertOfficeTemplate()">
                        📋 Chèn Mẫu Văn Phòng
                    </button>
                </div>
            </div>

            <!-- Textarea được thay thế bởi CKEditor -->
            <textarea id="full_spec" name="full_spec" class="pixel-input" rows="12"><?= htmlspecialchars($valFullSpec) ?></textarea>

            <p class="form-hint" style="margin-top: 6px; font-size: 0.8rem; color: var(--text-muted);">
                💡 Bạn có thể dùng thanh công cụ CKEditor bên trên để bôi đậm, chèn bảng thông số, hình ảnh, link hoặc nhấn nút <strong>"Source" (Mã nguồn)</strong> để xem/sửa trực tiếp mã HTML.
            </p>
        </div>

        <!-- Nút bấm Lưu -->
        <div class="form-actions" style="margin-top: 25px; display: flex; gap: 12px;">
            <button type="submit" class="pixel-btn pixel-btn-success" style="font-size: 1rem; padding: 12px 24px;">
                💾 <?= $isEdit ? 'LƯU THAY ĐỔI LAPTOP' : 'LƯU LAPTOP MỚI' ?>
            </button>
            <a href="index.php?page=products_list" class="pixel-btn pixel-btn-secondary" style="padding: 12px 20px;">
                ❌ HỦY BỎ
            </a>
        </div>
    </form>
</div>

<!-- Khởi tạo CKEditor 4 cho textarea #full_spec -->
<script>
    // Cấu hình CKEditor phong cách trực quan & đầy đủ công cụ
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('full_spec', {
            height: 350,
            language: 'vi',
            toolbar: [{
                    name: 'document',
                    items: ['Source', '-', 'Preview', 'Templates']
                },
                {
                    name: 'clipboard',
                    items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                },
                {
                    name: 'editing',
                    items: ['Find', 'Replace', '-', 'SelectAll']
                },
                '/',
                {
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
                },
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
                },
                {
                    name: 'links',
                    items: ['Link', 'Unlink']
                },
                {
                    name: 'insert',
                    items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar']
                },
                '/',
                {
                    name: 'styles',
                    items: ['Styles', 'Format', 'Font', 'FontSize']
                },
                {
                    name: 'colors',
                    items: ['TextColor', 'BGColor']
                },
                {
                    name: 'tools',
                    items: ['Maximize', 'ShowBlocks']
                }
            ],
            // Cho phép tất cả nội dung thẻ an toàn
            allowedContent: true
        });

        // Đồng bộ dữ liệu CKEditor vào textarea trước khi submit form
        var form = document.getElementById('richTextProductForm');
        if (form) {
            form.addEventListener('submit', function() {
                for (var instanceName in CKEDITOR.instances) {
                    CKEDITOR.instances[instanceName].updateElement();
                }
            });
        }
    }

    // Hàm chèn mẫu bảng cấu hình Gaming
    function insertGamingTemplate() {
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.full_spec) {
            var html = `<h3>🔥 LAPTOP GAMING CHIẾN MỌI TỰA GAME</h3>
<p>Dòng laptop gaming hiệu năng cực đỉnh với công nghệ tản nhiệt 2 quạt thế hệ mới và màn hình tần số quét siêu cao.</p>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:10px;">
    <thead>
        <tr style="background-color:#e6005c; color:#ffffff;">
            <th style="width:30%;">Tiêu Chí</th>
            <th>Cấu Hình Chi Tiết</th>
        </tr>
    </thead>
    <tbody>
        <tr><td><strong>Bộ Xử Lý (CPU)</strong></td><td>Intel Core i7-13700H (14 nhân, 20 luồng, Turbo 5.0GHz)</td></tr>
        <tr><td><strong>Card Đồ Họa (GPU)</strong></td><td>NVIDIA GeForce RTX 4070 8GB GDDR6 (TGP 140W)</td></tr>
        <tr><td><strong>Bộ Nhớ RAM</strong></td><td>32GB DDR5 5200MHz Dual Channel</td></tr>
        <tr><td><strong>Lưu Trữ SSD</strong></td><td>1TB SSD M.2 PCIe 4.0 Super Fast</td></tr>
        <tr><td><strong>Màn Hình</strong></td><td>16.0 inch QHD+ (2560 x 1600), 240Hz, 3ms, G-Sync, 100% DCI-P3</td></tr>
        <tr><td><strong>Bàn Phím</strong></td><td>RGB 4-zone có bàn phím số riêng biệt</td></tr>
        <tr><td><strong>Trọng Lượng &amp; Pin</strong></td><td>2.35 kg - Pin 90Whr hỗ trợ sạc nhanh</td></tr>
    </tbody>
</table>`;
            CKEDITOR.instances.full_spec.setData(html);
        }
    }

    // Hàm chèn mẫu bảng cấu hình Văn phòng - Mỏng nhẹ
    function insertOfficeTemplate() {
        if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.full_spec) {
            var html = `<h3>💼 LAPTOP DOANH NHÂN &amp; VĂN PHÒNG SIÊU NHẸ</h3>
<p>Thiết kế vỏ nhôm nguyên khối sang trọng, bảo mật vân tay một chạm cùng thời lượng pin lên đến 18 tiếng liên tục.</p>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:10px;">
    <thead>
        <tr style="background-color:#0099ff; color:#ffffff;">
            <th style="width:30%;">Mục Đánh Giá</th>
            <th>Thông Số Thực Tế</th>
        </tr>
    </thead>
    <tbody>
        <tr><td><strong>Bộ Vi Xử Lý</strong></td><td>Apple M3 Chip / Intel Core Ultra 7 155H</td></tr>
        <tr><td><strong>Bộ Nhớ Trong</strong></td><td>16GB LPDDR5X Siêu Tiết Kiệm Điện</td></tr>
        <tr><td><strong>Ổ Cứng</strong></td><td>512GB SSD Siêu Tốc</td></tr>
        <tr><td><strong>Màn Hình</strong></td><td>14.0 inch OLED 3K (2880 x 1800), 120Hz, 100% DCI-P3, HDR 600</td></tr>
        <tr><td><strong>Cổng Kết Nối</strong></td><td>2x Thunderbolt 4 / USB-C, 1x USB-A 3.2, 1x HDMI 2.1, Jack 3.5mm</td></tr>
        <tr><td><strong>Khối Lượng</strong></td><td>1.19 kg - Mỏng chỉ 13.9 mm</td></tr>
    </tbody>
</table>`;
            CKEDITOR.instances.full_spec.setData(html);
        }
    }
</script>