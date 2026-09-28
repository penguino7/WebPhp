<?php

/**
 * Trang Quản Lý Danh Sách Laptop (Products List) - Bài 14
 */

require_once __DIR__ . '/../libs/connect.php';
require_once __DIR__ . '/../libs/auth.php';
require_once __DIR__ . '/../libs/helper.php';

if (!isset($conn) || !$conn) {
    $conn = getDBConnection();
}

/**
 * Render trang danh sách laptop quản trị
 * @param mysqli $conn
 */
function renderProductsListPage($conn)
{
    $keyword = $_GET['keyword'] ?? '';
    $catId = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;
    $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
    $limit = 8;

    $data = getAdminProducts($conn, $keyword, $catId, $page, $limit);
    $products = $data['products'];
    $totalPages = $data['total_pages'];
    $currentPage = $data['current_page'];
    $totalRecords = $data['total_records'];

    $categories = getAllCategoriesWithCount($conn);
    $msg = $_GET['msg'] ?? '';
    $msgType = $_GET['msg_type'] ?? 'info';

    $baseUrl = "index.php?page=products_list&keyword=" . urlencode($keyword) . "&cat_id={$catId}";
?>
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">
                <span>💻</span> QUẢN LÝ SẢN PHẨM LAPTOP (<?= $totalRecords ?> máy)
            </h2>
            <div class="header-tools">
                <a href="index.php?page=products_form" class="btn-pixel btn-success-px">
                    ⚡ + Thêm Laptop Mới
                </a>
            </div>
        </div>

        <?php if (!empty($msg)): ?>
            <?php renderAlert($msgType, $msg); ?>
        <?php endif; ?>

        <!-- Bộ Lọc & Tìm Kiếm -->
        <div class="filter-box" style="background:var(--px-card-sub); padding:14px; border:2px solid var(--px-border); margin-bottom:18px;">
            <form action="index.php" method="GET" style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
                <input type="hidden" name="page" value="products_list">

                <!-- Dropdown lọc theo Hãng -->
                <div style="flex:1; min-width:180px; max-width:250px;">
                    <select name="cat_id" style="width:100%; padding:8px 10px; background:var(--px-input-bg); border:2px solid var(--px-border); color:var(--px-text-main); font-family:var(--font-pixel);">
                        <option value="0">-- Tất cả hãng máy --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['category_id'] ?>" <?= ($catId === (int)$c['category_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['category_name']) ?> (<?= $c['product_count'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Input Tìm kiếm từ khóa -->
                <div style="flex:2; min-width:220px;">
                    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Nhập tên laptop, CPU, RAM, RTX..." style="width:100%; padding:8px 12px; background:var(--px-input-bg); border:2px solid var(--px-border); color:var(--px-text-main); font-family:var(--font-pixel);">
                </div>

                <!-- Nút Submit Tìm kiếm -->
                <div>
                    <button type="submit" class="btn-pixel btn-primary-px">
                        🔍 Tìm Kiếm
                    </button>
                    <?php if (!empty($keyword) || $catId > 0): ?>
                        <a href="index.php?page=products_list" class="btn-pixel btn-dark-px" title="Xóa bộ lọc">
                            ✖ Xóa Lọc
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Bảng Danh Sách Laptop -->
        <?php if (empty($products)): ?>
            <div class="empty-box">
                <p>Không tìm thấy sản phẩm laptop nào phù hợp với điều kiện tìm kiếm.</p>
                <div style="margin-top:12px;">
                    <a href="index.php?page=products_list" class="btn-pixel btn-primary-px">Xem tất cả</a>
                    <a href="index.php?page=products_form" class="btn-pixel btn-success-px">+ Thêm mới</a>
                </div>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="pixel-table">
                    <thead>
                        <tr>
                            <th width="45">ID</th>
                            <th width="65">Ảnh</th>
                            <th>Tên Laptop</th>
                            <th width="140">Hãng</th>
                            <th width="130">Giá bán</th>
                            <th>Thông số tóm tắt</th>
                            <th width="130" style="text-align:center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p):
                            $imgSrc = "../Bai13/images/" . htmlspecialchars($p['image']);
                            $pId = (int)$p['product_id'];
                        ?>
                            <tr>
                                <td><strong>#<?= $pId ?></strong></td>
                                <td>
                                    <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="table-img-thumb" onerror="this.onerror=null; this.src='../Bai13/images/laptop_default.png';">
                                </td>
                                <td>
                                    <strong style="color:var(--px-title-color); font-size:14.5px;"><?= htmlspecialchars($p['product_name']) ?></strong>
                                </td>
                                <td>
                                    <span class="cat-badge"><?= htmlspecialchars($p['category_name']) ?></span>
                                </td>
                                <td>
                                    <strong style="color:var(--px-price); font-size:15px;"><?= formatPrice($p['price']) ?></strong>
                                    <?php if (!empty($p['old_price']) && $p['old_price'] > $p['price']): ?>
                                        <div style="font-size:11px; color:var(--px-price-old); text-decoration:line-through;">
                                            <?= formatPrice($p['old_price']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="font-size:12px; color:var(--px-text-muted);"><?= htmlspecialchars($p['summary_spec']) ?></span>
                                </td>
                                <td>
                                    <div class="table-actions" style="justify-content:center;">
                                        <a href="index.php?page=products_form&id=<?= $pId ?>" class="btn-pixel btn-warning-px" title="Sửa">
                                            ✏️
                                        </a>
                                        <a href="index.php?page=products_delete&id=<?= $pId ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa laptop #<?= $pId ?> \'<?= htmlspecialchars($p['product_name']) ?>\'?');" class="btn-pixel btn-danger-px" title="Xóa">
                                            🗑️
                                        </a>
                                        <a href="../Bai13/index.php?page=productDetail&id=<?= $pId ?>" target="_blank" class="btn-pixel btn-dark-px" title="Xem ngoài web">
                                            👁️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Phân Trang -->
            <?php renderPagination($currentPage, $totalPages, $baseUrl); ?>
        <?php endif; ?>
    </div>
<?php
}

renderProductsListPage($conn);
?>