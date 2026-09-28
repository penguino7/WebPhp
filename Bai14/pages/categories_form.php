<?php

/**
 * Form Thêm Mới / Chỉnh Sửa Danh Mục Hãng (Category Form) - Bài 14
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/auth.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Render Form Thêm / Sửa Hãng
 * @param mysqli $conn
 */
function renderCategoryFormPage($conn)
{
    $catId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $isEdit = ($catId > 0);
    $category = null;
    $errorMsg = '';
    $successMsg = '';

    if ($isEdit) {
        $category = getCategoryById($conn, $catId);
        if (!$category) {
            renderAlert('danger', 'Danh mục hãng cần sửa không tồn tại!');
            echo "<a href='index.php?page=categories_list' class='btn-pixel btn-primary-px'>⬅ Quay lại danh sách</a>";
            return;
        }
    }

    $nameVal = $category['category_name'] ?? '';
    $descVal = $category['description'] ?? '';

    // Xử lý submit form
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
        $nameVal = trim($_POST['category_name'] ?? '');
        $descVal = trim($_POST['description'] ?? '');

        $data = [
            'category_name' => $nameVal,
            'description'   => $descVal
        ];

        if ($isEdit) {
            $res = updateCategory($conn, $catId, $data);
        } else {
            $res = insertCategory($conn, $data);
        }

        if ($res['success']) {
            $encodedMsg = urlencode($res['message']);
            header("Location: index.php?page=categories_list&msg={$encodedMsg}&msg_type=success");
            exit();
        } else {
            $errorMsg = $res['message'];
        }
    }

    $formTitle = $isEdit ? "✏️ CHỈNH SỬA HÃNG LAPTOP #{$catId}" : "➕ THÊM MỚI HÃNG LAPTOP";
    $btnLabel = $isEdit ? "💾 CẬP NHẬT THAY ĐỔI" : "⚡ LƯU HÃNG MỚI";
?>
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">
                <span>📁</span> <?= $formTitle ?>
            </h2>
            <div class="header-tools">
                <a href="index.php?page=categories_list" class="btn-pixel btn-dark-px">
                    ⬅ Quay lại danh sách
                </a>
            </div>
        </div>

        <?php if (!empty($errorMsg)): ?>
            <?php renderAlert('danger', $errorMsg); ?>
        <?php endif; ?>

        <form action="" method="POST" class="pixel-form">
            <div class="form-group">
                <label for="category_name">
                    <span class="label-icon">🏷️</span> TÊN HÃNG SẢN XUẤT: <span style="color:var(--px-pink)">*</span>
                </label>
                <input type="text" id="category_name" name="category_name" value="<?= htmlspecialchars($nameVal) ?>" placeholder="vd: Laptop ASUS ROG, Laptop MSI Gaming, Laptop RAZER..." required>
                <div class="form-hint">Nhập tên thương hiệu hoặc dòng máy độc quyền.</div>
            </div>

            <div class="form-group">
                <label for="description">
                    <span class="label-icon">📝</span> MÔ TẢ / GIỚI THIỆU HÃNG:
                </label>
                <textarea id="description" name="description" placeholder="Nhập tóm tắt giới thiệu về hãng sản xuất, chính sách bảo hành hoặc điểm nổi bật..."><?= htmlspecialchars($descVal) ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-pixel btn-success-px">
                    <?= $btnLabel ?>
                </button>
                <a href="index.php?page=categories_list" class="btn-pixel btn-dark-px">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
<?php
}

renderCategoryFormPage($conn);
?>