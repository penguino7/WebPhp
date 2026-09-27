<?php

/**
 * TRANG XỬ LÝ MA TRẬN 2 CHIỀU (pages/matrix.php)
 */

// 1. Nhúng thư viện hàm xử lý ma trận
require_once __DIR__ . '/../libs/xuLyMatran.php';

// Khởi tạo giá trị mặc định cho 2 ma trận A và B (3x3)
$mA = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
];

$mB = [
    [9, 8, 7],
    [6, 5, 4],
    [3, 2, 1]
];

$results = null;

// 2. Xử lý khi người dùng bấm nút Tính toán
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    // Lấy dữ liệu gửi lên và ép kiểu số thực
    if (isset($_POST['a']) && is_array($_POST['a'])) {
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $mA[$i][$j] = floatval($_POST['a'][$i][$j] ?? 0);
            }
        }
    }

    if (isset($_POST['b']) && is_array($_POST['b'])) {
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $mB[$i][$j] = floatval($_POST['b'][$i][$j] ?? 0);
            }
        }
    }

    // Thực hiện tính toán bằng thư viện hàm
    $results = [
        'tong'        => tinhMatranTong($mA, $mB),
        'hieu'        => tinhMatranHieu($mA, $mB),
        'tich'        => tinhMatranTich($mA, $mB),
        'cheoChinhA'  => tongTrenCheoChinh($mA),
        'cheoChinhB'  => tongTrenCheoChinh($mB),
        'cheoPhuA'    => tongTrenCheoPhu($mA),
        'cheoPhuB'    => tongTrenCheoPhu($mB),
        'maxA'        => maxMatran($mA),
        'maxB'        => maxMatran($mB),
        'minA'        => minMatran($mA),
        'minB'        => minMatran($mB),
    ];
}

/**
 * Hàm phụ trợ hiển thị bảng nhập ma trận 3x3 ra HTML
 */
function renderMatrixInput(string $name, array $matrix)
{
    echo '<table class="matrix-table">';
    for ($i = 0; $i < 3; $i++) {
        echo '<tr>';
        for ($j = 0; $j < 3; $j++) {
            $val = htmlspecialchars((string)($matrix[$i][$j] ?? 0));
            echo "<td><input type='number' step='any' name='{$name}[$i][$j]' value='$val' required></td>";
        }
        echo '</tr>';
    }
    echo '</table>';
}

/**
 * Hàm phụ trợ hiển thị bảng ma trận 3x3 ra HTML
 */
function renderMatrixTable(array $matrix)
{
    echo '<table class="result-matrix-table">';
    for ($i = 0; $i < 3; $i++) {
        echo '<tr>';
        for ($j = 0; $j < 3; $j++) {
            echo '<td>' . htmlspecialchars((string)$matrix[$i][$j]) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}
?>

<div class="array-form-container" style="max-width: 720px;">
    <div class="form-header-title">THAO TÁC TRÊN MA TRẬN 2 CHIỀU (3x3)</div>

    <p style="text-align: center; font-size: 13px; color: #666; margin-bottom: 20px;">
        Nhập các phần tử cho <strong>Ma trận A</strong> và <strong>Ma trận B</strong> để thực hiện các phép toán đại số ma trận.
    </p>

    <form method="POST" action="index.php?page=matrix">
        <div class="matrix-container">
            <!-- Ma trận A -->
            <div class="matrix-box">
                <h4>Ma trận A (3x3)</h4>
                <?php renderMatrixInput('a', $mA); ?>
            </div>

            <!-- Ma trận B -->
            <div class="matrix-box">
                <h4>Ma trận B (3x3)</h4>
                <?php renderMatrixInput('b', $mB); ?>
            </div>
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn-submit">Thực hiện tính toán</button>
            <button type="button" class="btn-reset" onclick="window.location.href='index.php?page=matrix'">Đặt lại mặc định</button>
        </div>
    </form>
</div>

<?php if ($results !== null): ?>
    <div class="result-display-container" style="max-width: 720px;">
        <div class="form-header-title" style="color: #28a745; border-color: #d4edda;">KẾT QUẢ TÍNH TOÁN MA TRẬN</div>

        <!-- 1. Hiển thị các ma trận kết quả: Tổng, Hiệu, Tích -->
        <div class="results-grid" style="margin-bottom: 25px;">
            <div class="result-item">
                <h5>Tổng (A + B)</h5>
                <?php renderMatrixTable($results['tong']); ?>
            </div>

            <div class="result-item">
                <h5>Hiệu (A - B)</h5>
                <?php renderMatrixTable($results['hieu']); ?>
            </div>

            <div class="result-item">
                <h5>Tích (A &times; B)</h5>
                <?php renderMatrixTable($results['tich']); ?>
            </div>
        </div>

        <div class="form-header-title" style="font-size: 14px; margin-top: 20px; color: #333;">CÁC THÔNG SỐ ĐẶC TRƯNG</div>

        <!-- 2. Thống kê chéo chính, chéo phụ, Max, Min của từng ma trận -->
        <div class="result-row">
            <div class="result-label">Tổng đường chéo chính:</div>
            <div class="result-value">
                Ma trận A = <strong><?= $results['cheoChinhA'] ?></strong> |
                Ma trận B = <strong><?= $results['cheoChinhB'] ?></strong>
            </div>
        </div>

        <div class="result-row">
            <div class="result-label">Tổng đường chéo phụ:</div>
            <div class="result-value">
                Ma trận A = <strong><?= $results['cheoPhuA'] ?></strong> |
                Ma trận B = <strong><?= $results['cheoPhuB'] ?></strong>
            </div>
        </div>

        <div class="result-row">
            <div class="result-label">Phần tử lớn nhất (MAX):</div>
            <div class="result-value" style="color: #198754;">
                Ma trận A = <strong><?= $results['maxA'] ?></strong> |
                Ma trận B = <strong><?= $results['maxB'] ?></strong>
            </div>
        </div>

        <div class="result-row">
            <div class="result-label">Phần tử nhỏ nhất (MIN):</div>
            <div class="result-value" style="color: #dc3545;">
                Ma trận A = <strong><?= $results['minA'] ?></strong> |
                Ma trận B = <strong><?= $results['minB'] ?></strong>
            </div>
        </div>
    </div>
<?php endif; ?>