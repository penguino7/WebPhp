<h3 style="text-align: center; margin-bottom: 20px;">Sử dụng mảng để tính: Hiệu, Tổng, Tích 2 Ma Trận</h3>

<?php
function renderMatrixInput($matrixName, $matrixData = [])
{
    echo "<table class='matrix-table'>";
    for ($i = 0; $i < 3; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 3; $j++) {
            $val = htmlspecialchars($matrixData[$i][$j] ?? '');
            echo "<td>";
            echo "<input type='number' step='any' name='{$matrixName}[$i][$j]' value='$val' required>";
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function renderResultMatrix($matrix)
{
    echo "<table class='result-matrix-table'>";
    for ($i = 0; $i < 3; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 3; $j++) {
            $val = $matrix[$i][$j] ?? 0;
            echo "<td>" . htmlspecialchars($val) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

$tong = [];
$hieu = [];
$tich = [];
$hasCalculated = false;

if (isset($_POST['btnTinh'])) {
    $matrixA = $_POST['matrixA'] ?? [];
    $matrixB = $_POST['matrixB'] ?? [];

    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $valA = floatval($matrixA[$i][$j] ?? 0);
            $valB = floatval($matrixB[$i][$j] ?? 0);

            // 1. Tính Tổng (A + B)
            $tong[$i][$j] = $valA + $valB;

            // 2. Tính Hiệu (A - B)
            $hieu[$i][$j] = $valA - $valB;

            // 3. Tính Tích (A x B): Tổng (A[i][k] * B[k][j]) với k từ 0 đến 2
            $tich[$i][$j] = 0;
            for ($k = 0; $k < 3; $k++) {
                $a_ik = floatval($matrixA[$i][$k] ?? 0);
                $b_kj = floatval($matrixB[$k][$j] ?? 0);
                $tich[$i][$j] += $a_ik * $b_kj;
            }
        }
    }
    $hasCalculated = true;
}
?>

<form action="" method="POST" class="matrix-form">

    <div class="matrix-container">
        <div class="matrix-box">
            <h4>Nhập Ma trận 1:</h4>
            <?php renderMatrixInput('matrixA', $_POST['matrixA'] ?? []); ?>
        </div>
        <div class="matrix-box">
            <h4>Nhập Ma trận 2:</h4>
            <?php renderMatrixInput('matrixB', $_POST['matrixB'] ?? []); ?>
        </div>
    </div>

    <div class="form-buttons">
        <button type="button" class="btn-reset" onclick="window.location.href='index.php?page=array1'">Nhập Lại</button>
        <button type="submit" name="btnTinh" class="btn-submit">Tính</button>
    </div>

</form>

<?php if ($hasCalculated): ?>
    <div class="matrix-results">
        <h4>KẾT QUẢ PHÉP TÍNH</h4>
        <div class="results-grid">
            <div class="result-item">
                <h5>Ma trận Tổng (A + B)</h5>
                <?php renderResultMatrix($tong); ?>
            </div>
            <div class="result-item">
                <h5>Ma trận Hiệu (A - B)</h5>
                <?php renderResultMatrix($hieu); ?>
            </div>
            <div class="result-item">
                <h5>Ma trận Tích (A x B)</h5>
                <?php renderResultMatrix($tich); ?>
            </div>
        </div>
    </div>
<?php endif; ?>