<?php

/**
 * THƯ VIỆN HÀM XỬ LÝ MA TRẬN 2 CHIỀU (3x3)
 * (libs/xuLyMatran.php)
 */

// 1. Tìm phần tử lớn nhất trong ma trận (lấy max từng hàng -> lấy max của toàn bộ)
function maxMatran(array $matran)
{
    if (empty($matran)) {
        return 0;
    }
    return max(array_map('max', $matran));
}

// 2. Tìm phần tử nhỏ nhất trong ma trận (lấy min từng hàng -> lấy min của toàn bộ)
function minMatran(array $matran)
{
    if (empty($matran)) {
        return 0;
    }
    return min(array_map('min', $matran));
}

// 3. Tính tổng các phần tử trên đường chéo chính (A[i][i])
function tongTrenCheoChinh(array $matran)
{
    $sum = 0;
    $n = min(count($matran), count($matran[0] ?? []));
    for ($i = 0; $i < $n; $i++) {
        $sum += floatval($matran[$i][$i] ?? 0);
    }
    return $sum;
}

// 4. Tính tổng các phần tử trên đường chéo phụ (A[i][n - 1 - i])
function tongTrenCheoPhu(array $matran)
{
    $sum = 0;
    $n = min(count($matran), count($matran[0] ?? []));
    for ($i = 0; $i < $n; $i++) {
        $sum += floatval($matran[$i][$n - 1 - $i] ?? 0);
    }
    return $sum;
}

// 5. Tính tổng 2 ma trận (A + B)
function tinhMatranTong(array $m1, array $m2)
{
    $res = [];
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $res[$i][$j] = floatval($m1[$i][$j] ?? 0) + floatval($m2[$i][$j] ?? 0);
        }
    }
    return $res;
}

// 6. Tính hiệu 2 ma trận (A - B)
function tinhMatranHieu(array $m1, array $m2)
{
    $res = [];
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $res[$i][$j] = floatval($m1[$i][$j] ?? 0) - floatval($m2[$i][$j] ?? 0);
        }
    }
    return $res;
}

// 7. Tính tích 2 ma trận (A x B)
function tinhMatranTich(array $m1, array $m2)
{
    $res = [];
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $res[$i][$j] = 0;
            for ($k = 0; $k < 3; $k++) {
                $a_ik = floatval($m1[$i][$k] ?? 0);
                $b_kj = floatval($m2[$k][$j] ?? 0);
                $res[$i][$j] += $a_ik * $b_kj;
            }
        }
    }
    return $res;
}
