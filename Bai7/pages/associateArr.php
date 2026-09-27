<?php

/**
 * TRANG THAO TÁC TRÊN MẢNG KẾT HỢP (pages/associateArr.php)
 * Xử lý trực tiếp trên mảng thuần PHP (không dùng Session)
 */

// 1. Mảng kết hợp mẫu ban đầu (Bảng điểm môn học: Key => Value)
$arrData = [
    'Toan'      => 8.5,
    'VatLy'     => 7.0,
    'HoaHoc'    => 9.0,
    'TinHoc'    => 9.5,
    'TiengAnh'  => 8.0,
    'SinhHoc'   => 6.5,
];

$msgSuccess = '';
$msgInfo = '';
$msgError = '';
$searchResult = '';
$searchError = '';

// 2. Xử lý thao tác POST (Thêm mới, Tìm kiếm)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $actionPost = $_POST['action'] ?? '';

    // A. Thêm hoặc cập nhật phần tử vào mảng
    if ($actionPost === 'add') {
        $newKey = trim($_POST['new_key'] ?? '');
        $newVal = $_POST['new_value'] ?? '';

        if ($newKey === '' || $newVal === '') {
            $msgError = 'Vui lòng nhập đầy đủ cả Khóa (Key) và Giá trị (Value)!';
        } else {
            $arrData[$newKey] = floatval($newVal);
            $msgSuccess = 'Đã thêm phần tử mới: <strong>' . htmlspecialchars($newKey) . '</strong> &rArr; <strong>' . htmlspecialchars((string)$newVal) . '</strong> vào mảng!';
        }
    }

    // B. Tìm kiếm trong mảng (dùng array_key_exists hoặc array_search)
    if ($actionPost === 'search') {
        $keyword = trim($_POST['keyword'] ?? '');
        $searchType = $_POST['search_type'] ?? 'key';

        if ($keyword === '') {
            $searchError = 'Vui lòng nhập từ khóa để tìm kiếm!';
        } else {
            if ($searchType === 'key') {
                // Kiểm tra xem Key có tồn tại trong mảng không
                if (array_key_exists($keyword, $arrData)) {
                    $searchResult = 'Tìm thấy Khóa [<strong>' . htmlspecialchars($keyword) . '</strong>] có Giá trị là: <strong>' . htmlspecialchars((string)$arrData[$keyword]) . '</strong>';
                } else {
                    $searchError = 'Không tìm thấy Khóa nào có tên [<strong>' . htmlspecialchars($keyword) . '</strong>] trong mảng!';
                }
            } elseif ($searchType === 'value') {
                // Tìm Key tương ứng với Giá trị
                $matchedKey = array_search(floatval($keyword), $arrData);
                if ($matchedKey !== false) {
                    $searchResult = 'Tìm thấy Giá trị [<strong>' . htmlspecialchars($keyword) . '</strong>] tại Khóa: <strong>' . htmlspecialchars($matchedKey) . '</strong>';
                } else {
                    $searchError = 'Không tìm thấy phần tử nào có Giá trị bằng [<strong>' . htmlspecialchars($keyword) . '</strong>]!';
                }
            }
        }
    }
}

// 3. Xử lý thao tác GET Sắp xếp mảng (ksort, krsort, asort, arsort)
if (isset($_GET['sort'])) {
    $sortType = $_GET['sort'];
    switch ($sortType) {
        case 'ksort':
            ksort($arrData);
            $msgInfo = 'Đã sắp xếp mảng theo <strong>Key tăng dần (ksort)</strong>.';
            break;
        case 'krsort':
            krsort($arrData);
            $msgInfo = 'Đã sắp xếp mảng theo <strong>Key giảm dần (krsort)</strong>.';
            break;
        case 'asort':
            asort($arrData);
            $msgInfo = 'Đã sắp xếp mảng theo <strong>Value tăng dần (asort)</strong>.';
            break;
        case 'arsort':
            arsort($arrData);
            $msgInfo = 'Đã sắp xếp mảng theo <strong>Value giảm dần (arsort)</strong>.';
            break;
    }
}
?>

<div class="array-form-container assoc-form-container">
    <div class="form-header-title">THAO TÁC TRÊN MẢNG KẾT HỢP (ASSOCIATIVE ARRAY)</div>

    <p class="page-subtitle">
        Thực hành các thao tác thêm, tìm kiếm, sắp xếp và thống kê trên mảng cặp <strong>Key &rArr; Value</strong>.
    </p>

    <!-- Thông báo kết quả thao tác -->
    <?php if (!empty($msgSuccess)): ?>
        <div class="alert-success"><?= $msgSuccess ?></div>
    <?php endif; ?>

    <?php if (!empty($msgInfo)): ?>
        <div class="alert-info"><?= $msgInfo ?></div>
    <?php endif; ?>

    <?php if (!empty($msgError)): ?>
        <div class="alert-error"><?= $msgError ?></div>
    <?php endif; ?>

    <!-- 1. Form Thêm / Cập nhật phần tử mới -->
    <div class="assoc-card">
        <h4 class="assoc-card-title">1. Thêm / Cập nhật phần tử (Key &rArr; Value)</h4>
        <form method="POST" action="index.php?page=associateArr" class="assoc-inline-form">
            <input type="hidden" name="action" value="add">
            <input
                type="text"
                name="new_key"
                class="assoc-input"
                placeholder="Nhập Key (vd: LichSu)"
                required>
            <input
                type="number"
                step="0.1"
                name="new_value"
                class="assoc-input"
                placeholder="Nhập Value (vd: 8.0)"
                required>
            <button type="submit" class="btn-submit btn-sm">+ Thêm vào mảng</button>
        </form>
    </div>

    <!-- 2. Form Tìm kiếm -->
    <div class="assoc-card">
        <h4 class="assoc-card-title">2. Tìm kiếm trong mảng</h4>
        <form method="POST" action="index.php?page=associateArr" class="assoc-inline-form">
            <input type="hidden" name="action" value="search">
            <input
                type="text"
                name="keyword"
                class="assoc-input"
                placeholder="Nhập từ khóa cần tìm..."
                value="<?= htmlspecialchars($_POST['keyword'] ?? '') ?>"
                required>
            <select name="search_type" class="assoc-select">
                <option value="key" <?= (($_POST['search_type'] ?? '') === 'key') ? 'selected' : '' ?>>Tìm theo Key (array_key_exists)</option>
                <option value="value" <?= (($_POST['search_type'] ?? '') === 'value') ? 'selected' : '' ?>>Tìm theo Value (array_search)</option>
            </select>
            <button type="submit" class="btn-submit btn-info btn-sm">Tìm kiếm</button>
        </form>

        <?php if (!empty($searchResult)): ?>
            <div class="alert-success" style="margin-top: 12px; margin-bottom: 0;"><?= $searchResult ?></div>
        <?php endif; ?>

        <?php if (!empty($searchError)): ?>
            <div class="alert-error" style="margin-top: 12px; margin-bottom: 0;"><?= $searchError ?></div>
        <?php endif; ?>
    </div>

    <!-- 3. Các nút chức năng Sắp xếp -->
    <div class="assoc-card">
        <h4 class="assoc-card-title">3. Sắp xếp mảng kết hợp</h4>
        <div class="assoc-btn-group">
            <button type="button" class="btn-submit btn-purple btn-sm" onclick="window.location.href='index.php?page=associateArr&sort=ksort'">Key tăng dần (ksort)</button>
            <button type="button" class="btn-submit btn-purple btn-sm" onclick="window.location.href='index.php?page=associateArr&sort=krsort'">Key giảm dần (krsort)</button>
            <button type="button" class="btn-submit btn-orange btn-sm" onclick="window.location.href='index.php?page=associateArr&sort=asort'">Value tăng dần (asort)</button>
            <button type="button" class="btn-submit btn-orange btn-sm" onclick="window.location.href='index.php?page=associateArr&sort=arsort'">Value giảm dần (arsort)</button>
            <button type="button" class="btn-reset btn-sm" onclick="window.location.href='index.php?page=associateArr'">Đặt lại ban đầu</button>
        </div>
    </div>
</div>

<!-- 4. Khu vực hiển thị bảng dữ liệu & thống kê mảng kết hợp -->
<div class="result-display-container assoc-result-container">
    <div class="form-header-title title-success">DANH SÁCH PHẦN TỬ TRONG MẢNG KẾT HỢP</div>

    <!-- Bảng danh sách Key - Value -->
    <table class="assoc-table">
        <thead>
            <tr>
                <th style="text-align: center; width: 60px;">STT</th>
                <th style="text-align: left;">Khóa (Key)</th>
                <th style="text-align: right; width: 120px;">Giá trị (Value)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stt = 1;
            foreach ($arrData as $k => $v):
            ?>
                <tr>
                    <td class="col-stt"><?= $stt++ ?></td>
                    <td class="col-key"><?= htmlspecialchars($k) ?></td>
                    <td class="col-val"><?= htmlspecialchars((string)$v) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Thống kê nhanh mảng -->
    <div class="form-header-title title-sub">THỐNG KÊ MẢNG KẾT HỢP</div>

    <div class="result-row">
        <div class="result-label">Tổng số phần tử (count):</div>
        <div class="result-value"><?= count($arrData) ?></div>
    </div>

    <div class="result-row">
        <div class="result-label">Giá trị lớn nhất (max):</div>
        <div class="result-value val-max"><?= max($arrData) ?></div>
    </div>

    <div class="result-row">
        <div class="result-label">Giá trị nhỏ nhất (min):</div>
        <div class="result-value val-min"><?= min($arrData) ?></div>
    </div>

    <div class="result-row">
        <div class="result-label">Trung bình cộng các giá trị:</div>
        <div class="result-value"><?= round(array_sum($arrData) / count($arrData), 2) ?></div>
    </div>
</div>