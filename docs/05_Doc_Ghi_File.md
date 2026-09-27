# CHUYÊN ĐỀ 5: THAO TÁC ĐỌC VÀ GHI FILE TRONG PHP (BÀI 8)

---

## 1. Bản chất lưu trữ File Text (`.txt`)

* Dữ liệu trong file văn bản được lưu trữ tuần tự thành các dòng text.
* Mỗi dòng kết thúc bằng ký tự xuống dòng:
  * Linux/macOS: `\n` (LF)
  * Windows: `\r\n` (CRLF)
  * Trong PHP ta nên dùng hằng số `PHP_EOL` (End-Of-Line) để tương thích đa nền tảng.

---

## 2. Quy ước lưu trữ dữ liệu theo khối dòng (Bài 8 - `student.txt`)

Mỗi sinh viên được lưu trữ cố định gồm **3 dòng liên tiếp**:
* Dòng 1: Tên
* Dòng 2: Địa chỉ
* Dòng 3: Tuổi

*Nội dung mẫu:*
```text
Pham Minh
Ha Noi
18
Nguyen Du
Hue
500
```

---

## 3. Các phương pháp ĐỌC File trong PHP

### 3.1. Phương pháp 1: Dùng hàm `file()` (Gọn gàng & Hiện đại) ⭐
Đọc toàn bộ file thành một mảng, mỗi dòng là một phần tử:

```php
$filePath = __DIR__ . '/../student.txt';

if (file_exists($filePath)) {
    // FILE_IGNORE_NEW_LINES: Cắt bỏ ký tự \n ở cuối mỗi dòng
    // FILE_SKIP_EMPTY_LINES: Tự động bỏ qua các dòng trống
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    $students = [];
    $totalLines = count($lines);

    // Duyệt qua mảng với bước nhảy +3 để gom nhóm sinh viên
    for ($i = 0; $i < $totalLines; $i += 3) {
        $students[] = [
            'ten'    => $lines[$i] ?? '',
            'diachi' => $lines[$i + 1] ?? '',
            'tuoi'   => $lines[$i + 2] ?? '',
        ];
    }
}
```

### 3.2. Phương pháp 2: Dùng con trỏ file (`fopen` + `fgets` + `fclose`)
```php
$handle = fopen($filePath, "r"); // "r" = Read
$students = [];

if ($handle) {
    while (!feof($handle)) { // feof = Kiểm tra đến cuối file chưa
        $ten = trim(fgets($handle));
        $diachi = trim(fgets($handle));
        $tuoi = trim(fgets($handle));
        
        if ($ten !== '') {
            $students[] = [
                'ten'    => $ten,
                'diachi' => $diachi,
                'tuoi'   => $tuoi
            ];
        }
    }
    fclose($handle); // Luôn đóng file giải phóng bộ nhớ
}
```

---

## 4. Các phương pháp GHI File trong PHP

### 4.1. Phương pháp 1: Dùng hàm `file_put_contents()` (Khuyên dùng) ⭐
Ghi dữ liệu vào file chỉ với 1 dòng lệnh:

```php
$filePath = __DIR__ . '/../student.txt';
$content = $ten . PHP_EOL . $diachi . PHP_EOL . intval($tuoi) . PHP_EOL;

// FILE_APPEND: Ghi nối tiếp vào cuối file (không xóa dữ liệu cũ)
// LOCK_EX: Khóa độc quyền trong lúc ghi để tránh xung đột
$result = file_put_contents($filePath, $content, FILE_APPEND | LOCK_EX);

if ($result !== false) {
    echo "Ghi file thành công!";
}
```

### 4.2. Phương pháp 2: Dùng con trỏ file (`fopen` mode `'a'` + `fwrite` + `fclose`)
```php
$handle = fopen($filePath, "a"); // "a" = Append (Ghi tiếp vào cuối)
if ($handle) {
    fwrite($handle, $ten . PHP_EOL);
    fwrite($handle, $diachi . PHP_EOL);
    fwrite($handle, intval($tuoi) . PHP_EOL);
    fclose($handle);
}
```

---

## 5. Bảng tổng kết các chế độ mở file (`fopen` modes)

| Chế độ (Mode) | Ý nghĩa | Vị trí con trỏ | Tạo file mới nếu chưa có? | Xóa dữ liệu cũ? |
| :--- | :--- | :--- | :--- | :--- |
| **`'r'`** | Chỉ đọc (Read) | Đầu file | **Không** (Báo lỗi nếu không có file) | Không |
| **`'w'`** | Chỉ ghi (Write) | Đầu file | **Có** | **CÓ (Xóa sạch file cũ)** |
| **`'a'`** | Ghi tiếp (Append) | **Cuối file** | **Có** | **Không (Giữ nguyên dữ liệu cũ)** |
| **`'r+'`** | Đọc và Ghi | Đầu file | **Không** | Không |
| **`'w+'`** | Đọc và Ghi | Đầu file | **Có** | **CÓ** |
| **`'a+'`** | Đọc và Ghi tiếp | Cuối file khi ghi | **Có** | Không |
