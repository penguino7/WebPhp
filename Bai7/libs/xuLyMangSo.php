<?php

/**
 * THƯ VIỆN HÀM XỬ LÝ MẢNG 1 CHIỀU
 * (libs/xuLyMangSo.php)
 */

// 1. Tìm giá trị nhỏ nhất trong mảng
function minArray(array $mangSo)
{
    if (empty($mangSo)) return 0;
    return min($mangSo);
}

// 2. Tìm giá trị lớn nhất trong mảng
function maxArray(array $mangSo)
{
    if (empty($mangSo)) return 0;
    return max($mangSo);
}

// 3. Tính tổng các phần tử trong mảng
function tongDay(array $mangSo)
{
    return array_sum($mangSo);
}

// 4. Tính giá trị trung bình cộng của mảng
function avgDay(array $mangSo)
{
    $count = count($mangSo);
    if ($count === 0) return 0;
    return array_sum($mangSo) / $count;
}

// 5. Sắp xếp mảng tăng dần
function sortDay(array $mangSo)
{
    sort($mangSo);
    return $mangSo;
}

// 6. Đảo ngược thứ tự mảng
function daoNguocDay(array $mangSo)
{
    return array_reverse($mangSo);
}
