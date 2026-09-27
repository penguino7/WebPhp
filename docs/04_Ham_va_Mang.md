# CHUYÊN ĐỀ 4: HÀM (FUNCTION) & MẢNG (ARRAY) TRONG PHP (BÀI 7)

---

## 1. Tổ chức Thư viện Hàm (Function Library) trong PHP

### 1.1. Nguyên tắc tổ chức:
* Các hàm xử lý nghiệp vụ toán học, logic không để lẫn trong file giao diện HTML mà được gom vào thư mục riêng: `libs/`.
* Các trang giao diện trong `pages/` chỉ cần gọi `require_once __DIR__ . '/../libs/tên_file.php'` để tái sử dụng.

---

## 2. Mảng 1 chiều (1D Array - `libs/xuLyMangSo.php`)

### 2.1. Các hàm cơ bản:
```php
// 1. Tìm giá trị nhỏ nhất (MIN)
function minArray(array $arr) {
    return empty($arr) ? 0 : min($arr);
}

// 2. Tìm giá trị lớn nhất (MAX)
function maxArray(array $arr) {
    return empty($arr) ? 0 : max($arr);
}

// 3. Tính tổng các phần tử
function tongDay(array $arr) {
    return array_sum($arr);
}

// 4. Tính trung bình cộng
function avgDay(array $arr) {
    return empty($arr) ? 0 : array_sum($arr) / count($arr);
}

// 5. Sắp xếp mảng tăng dần
function sortDay(array $arr) {
    sort($arr);
    return $arr;
}

// 6. Đảo ngược thứ tự dãy
function daoNguocDay(array $arr) {
    return array_reverse($arr);
}
```

### 2.2. Kỹ thuật chuyển chuỗi nhập từ Form thành mảng số:
```php
$rawInput = "3, 5, 1, 8, 2, 9";
$mangSo = array_map('floatval', array_map('trim', explode(',', $rawInput)));
```

---

## 3. Ma trận 2 chiều (2D Matrix - `libs/xuLyMatran.php`)

### 3.1. Tìm Max / Min ma trận với `array_map`:
Thay vì lồng 2 vòng lặp `for`, ta có thể tìm max/min của từng hàng rồi lấy max/min chung:
```php
function maxMatran(array $matran) {
    if (empty($matran)) return 0;
    return max(array_map('max', $matran));
}

function minMatran(array $matran) {
    if (empty($matran)) return 0;
    return min(array_map('min', $matran));
}
```

### 3.2. Đường chéo chính và Đường chéo phụ:
* **Đường chéo chính:** Các phần tử có chỉ số **Hàng = Cột** ($i = j$): $A[0][0], A[1][1], A[2][2]$.
  ```php
  function tongTrenCheoChinh(array $matran) {
      $sum = 0;
      $n = count($matran);
      for ($i = 0; $i < $n; $i++) {
          $sum += floatval($matran[$i][$i] ?? 0);
      }
      return $sum;
  }
  ```
* **Đường chéo phụ:** Các phần tử thỏa mãn công thức **$j = n - 1 - i$**: $A[0][2], A[1][1], A[2][0]$.
  ```php
  function tongTrenCheoPhu(array $matran) {
      $sum = 0;
      $n = count($matran);
      for ($i = 0; $i < $n; $i++) {
          $sum += floatval($matran[$i][$n - 1 - $i] ?? 0);
      }
      return $sum;
  }
  ```

### 3.3. Tích 2 ma trận ($A \times B$): Hàng của A nhân với Cột của B
$$C[i][j] = \sum_{k=0}^{2} A[i][k] \times B[k][j]$$
```php
function tinhMatranTich(array $m1, array $m2) {
    $res = [];
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $res[$i][$j] = 0;
            for ($k = 0; $k < 3; $k++) {
                $res[$i][$j] += floatval($m1[$i][$k] ?? 0) * floatval($m2[$k][$j] ?? 0);
            }
        }
    }
    return $res;
}
```

---

## 4. Mảng kết hợp (Associative Array - `pages/associateArr.php`)

### 4.1. Bản chất:
Mảng gồm các cặp **`Key => Value`** (ví dụ: `['Toan' => 8.5, 'TinHoc' => 9.5]`).

### 4.2. Các hàm cốt lõi:

| Thao tác | Cú pháp / Hàm | Ý nghĩa |
| :--- | :--- | :--- |
| **Duyệt mảng** | `foreach ($arr as $key => $val)` | Duyệt qua từng cặp Key và Value |
| **Thêm / Sửa** | `$arr[$newKey] = $newVal;` | Thêm mới hoặc cập nhật nếu key đã có |
| **Kiểm tra Key** | `array_key_exists($key, $arr)` | Kiểm tra khóa `$key` có trong mảng không (trả về bool) |
| **Tìm theo Value**| `array_search($val, $arr)` | Tìm giá trị và trả về Key tương ứng |
| **Xóa phần tử** | `unset($arr[$key]);` | Xóa phần tử có khóa `$key` |
| **Sắp xếp Key tăng** | `ksort($arr);` | **K**ey **Sort** (A $\rightarrow$ Z) |
| **Sắp xếp Key giảm** | `krsort($arr);` | **K**ey **R**everse **Sort** (Z $\rightarrow$ A) |
| **Sắp xếp Value tăng**| `asort($arr);` | **A**ssociative **Sort** theo giá trị tăng dần |
| **Sắp xếp Value giảm**| `arsort($arr);` | **A**ssociative **R**everse **Sort** theo giá trị giảm dần |
