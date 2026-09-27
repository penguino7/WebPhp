<?php

/**
 * TRANG XỬ LÝ MẢNG 1 CHIỀU (pages/ar1Chieu.php)
 */

// 1. Nhúng thư viện hàm xử lý mảng
require_once __DIR__ . '/../libs/xuLyMangSo.php';

$rawInput = $_POST['dayso'] ?? '';
$error = '';
$results = null;

// 2. Xử lý khi người dùng bấm nút gửi form
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (trim($rawInput) === '') {
        $error = 'Vui lòng nhập dãy số!';
    } else {
        // Tách chuỗi theo dấu phẩy
        $parts = explode(',', $rawInput);
        $mangSo = [];

        foreach ($parts as $item) {
            $trimmed = trim($item);
            if ($trimmed !== '' && is_numeric($trimmed)) {
                $mangSo[] = floatval($trimmed);
            }
        }

        if (empty($mangSo)) {
            $error = 'Dãy số không hợp lệ. Vui lòng nhập các số cách nhau bởi dấu phẩy!';
        } else {
            // Thực hiện tính toán bằng các hàm trong thư viện
            $results = [
                'goc'       => implode(', ', $mangSo),
                'tong'      => tongDay($mangSo),
                'tbc'       => round(avgDay($mangSo), 2),
                'min'       => minArray($mangSo),
                'max'       => maxArray($mangSo),
                'tang_dan'  => implode(', ', sortDay($mangSo)),
                'dao_nguoc' => implode(', ', daoNguocDay($mangSo)),
            ];
        }
    }
}
?>

<div class="array-form-container">
    <div class="form-header-title">THAO TÁC TRÊN MẢNG 1 CHIỀU</div>

    <p style="text-align: center; font-size: 13px; color: #666; margin-bottom: 20px;">
        Nhập các số nguyên hoặc số thực, phân cách nhau bằng dấu phẩy <em>(Ví dụ: 3, 5, 1, 8, 2, 9, 4)</em>
    </p>

    <?php if (!empty($error)): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px 14px; border-radius: 4px; margin-bottom: 18px; font-size: 14px; border: 1px solid #f5c6cb;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=ar1Chieu">
        <div class="form-group">
            <label class="form-label" for="dayso">Nhập dãy số:</label>
            <div class="form-control-wrap">
                <input
                    type="text"
                    id="dayso"
                    name="dayso"
                    value="<?= htmlspecialchars($rawInput) ?>"
                    placeholder="Ví dụ: 3, 3, 3, 1, 3, 3, 5, 3, 3, 3"
                    required>
            </div>
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn-submit">Thực hiện tính toán</button>
            <a href="index.php?page=ar1Chieu" class="btn-reset" style="text-decoration: none; display: inline-block; text-align: center; line-height: 20px;">Làm mới</a>
        </div>
    </form>
</div>

<?php if ($results !== null): ?>
    <div class="result-display-container">
        <div class="form-header-title" style="color: #28a745; border-color: #d4edda;">KẾT QUẢ TÍNH TOÁN & XỬ LÝ MẢNG</div>

        <div class="result-row">
            <div class="result-label">Dãy số ban đầu:</div>
            <div class="result-value" style="color: #333;"><?= htmlspecialchars($results['goc']) ?></div>
        </div>

        <div class="result-row">
            <div class="result-label">Tổng các số:</div>
            <div class="result-value"><?= $results['tong'] ?></div>
        </div>

        <div class="result-row">
            <div class="result-label">Trung bình cộng:</div>
            <div class="result-value"><?= $results['tbc'] ?></div>
        </div>

        <div class="result-row">
            <div class="result-label">Số nhỏ nhất (MIN):</div>
            <div class="result-value" style="color: #dc3545;"><?= $results['min'] ?></div>
        </div>

        <div class="result-row">
            <div class="result-label">Số lớn nhất (MAX):</div>
            <div class="result-value" style="color: #198754;"><?= $results['max'] ?></div>
        </div>

        <div class="result-row">
            <div class="result-label">Sắp xếp tăng dần:</div>
            <div class="result-value"><?= htmlspecialchars($results['tang_dan']) ?></div>
        </div>

        <div class="result-row">
            <div class="result-label">Dãy số đảo ngược:</div>
            <div class="result-value"><?= htmlspecialchars($results['dao_nguoc']) ?></div>
        </div>
    </div>
<?php endif; ?>