# Thao Tác Đọc & Ghi File Văn Bản & Luồng Dữ Liệu Ứng Dụng (Data Flow)

---

## 1. Bản Chất Lưu Trữ File Text & Luồng Dữ Liệu Tệp (File Stream)

### 1.1. Luồng Dữ Liệu Tệp & Ký Tự Ngắt Dòng

Tập tin văn bản (`.txt`, `.csv`, `.log`) trên hệ điều hành thực chất là một chuỗi byte liên tục được lưu trữ trên ổ cứng. Để phân định giữa các dòng văn bản, mỗi hệ điều hành sử dụng các ký tự điều khiển xuống dòng khác nhau:

- **Windows:** Sử dụng cặp 2 ký tự **`\r\n`** (Carriage Return `\r` mã ASCII 13 + Line Feed `\n` mã ASCII 10 - CRLF).
- **Linux / macOS / Unix:** Sử dụng 1 ký tự **`\n`** (Line Feed - LF).
- **Chuẩn mực trong PHP:** Luôn sử dụng hằng số **`PHP_EOL`** (PHP End-Of-Line). PHP sẽ tự động chọn ký tự ngắt dòng chính xác tương ứng với hệ điều hành của máy chủ đang chạy.

---

### 1.2. Các Mô Hình Lưu Trữ Dữ Liệu Có Cấu Trúc Bằng File Text

Khi chưa tích hợp hệ quản trị cơ sở dữ liệu (MySQL/PostgreSQL), ta tổ chức dữ liệu vào file text theo 2 mô hình phổ biến:

#### Mô Hình A: Khối Nhiều Dòng Liên Tiếp (Multi-line Record)

Mỗi đối tượng (sinh viên) chiếm $N$ dòng liên tiếp nhau trên file:

_Ví dụ: Cấu trúc 5 dòng / bản ghi (Bài 9):_

```text
Pham Minh           <-- Dòng 0: Họ tên (Full name)
2012-08-12          <-- Dòng 1: Ngày sinh (Birthday)
Ha Noi              <-- Dòng 2: Địa chỉ (Address)
1.jpg               <-- Dòng 3: Tên file ảnh (Image)
class1              <-- Dòng 4: Lớp học (Class)
Nguyen Khoi         <-- Dòng 5: Họ tên (SV tiếp theo)
2012-08-11          <-- Dòng 6: Ngày sinh
TP.HCM              <-- Dòng 7: Địa chỉ
2.jpg               <-- Dòng 8: Tên file ảnh
class1              <-- Dòng 9: Lớp học
```

#### Mô Hình B: Dòng Phân Cách (Delimited / CSV Format)

Mỗi đối tượng nằm trọn vẹn trên 1 dòng duy nhất, các trường cách nhau bởi ký tự phân tách (ví dụ dấu `|` hoặc `,`):

```text
Pham Minh|2012-08-12|Ha Noi|1.jpg|class1
Nguyen Khoi|2012-08-11|TP.HCM|2.jpg|class1
```

---

### 1.3. Biểu Đồ Tuần Tự Toàn Bộ Luồng Đọc & Ghi File

```mermaid
sequenceDiagram
    autonumber
    actor Script as PHP Script
    participant Engine as PHP File Engine
    participant OS as Hệ Điều Hành (OS Kernel)
    participant Disk as Ổ Cứng (student.txt)

    Note over Script,Disk: 1. QUY TRÌNH ĐỌC DỮ LIỆU (READ FLOW)
    Script->>Engine: file_exists('student.txt')
    Engine->>OS: Kiểm tra file trên File System
    OS-->>Engine: File tồn tại (true)
    Script->>Engine: file('student.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    Engine->>OS: Đọc toàn bộ nội dung file
    OS->>Disk: Đọc dữ liệu từ đĩa
    Disk-->>OS: Trả về dòng byte
    OS-->>Engine: Buffer dữ liệu
    Engine->>Engine: Tự động tách dòng & loại bỏ \r\n
    Engine-->>Script: Trả về mảng $lines: ['Pham Minh', '2012-08-12', 'Ha Noi', '1.jpg', 'class1', ...]

    Note over Script,Disk: 2. QUY TRÌNH GHI NỐI TIẾP AN TOÀN (WRITE FLOW)
    Script->>Script: Chuẩn bị chuỗi 5 dòng kết thúc bằng PHP_EOL
    Script->>Engine: file_put_contents('student.txt', $data, FILE_APPEND | LOCK_EX)
    Engine->>OS: Yêu cầu Khóa Độc Quyền (Exclusive Lock - LOCK_EX)
    OS-->>Engine: Đã khóa file (Không cho tiến trình khác can thiệp)
    Engine->>OS: Ghi dữ liệu nối tiếp vào cuối file
    OS->>Disk: Ghi dữ liệu xuống ổ cứng
    Disk-->>OS: Ghi hoàn tất
    Engine->>OS: Mở khóa file (Unlock)
    Engine-->>Script: Trả về số byte đã ghi thành công (int)
```

---

## 2. Nhóm Hàm Kiểm Tra Trạng Thái & Đường Dẫn File

Trước khi thực hiện đọc hoặc ghi, bắt buộc phải kiểm tra trạng thái tệp để tránh phát sinh lỗi nghiêm trọng:

```php
$filePath = __DIR__ . '/../student.txt';

// 1. Kiểm tra file hoặc thư mục có tồn tại không
if (file_exists($filePath)) {
    // 2. Kiểm tra xem có đúng là file thông thường không (không phải là thư mục)
    if (is_file($filePath)) {
        // 3. Kiểm tra quyền đọc
        if (is_readable($filePath)) {
            echo "File có thể đọc được. Dung lượng: " . filesize($filePath) . " bytes";
        }
        // 4. Kiểm tra quyền ghi
        if (is_writable($filePath)) {
            echo "File có thể ghi được.";
        }
    }
}
```

| Tên Hàm             | Cú Pháp                              | Mô Tả & Giá Trị Trả Về                                                                       |
| :------------------ | :----------------------------------- | :------------------------------------------------------------------------------------------- |
| **`file_exists()`** | `file_exists(string $path): bool`    | Kiểm tra xem file hoặc thư mục có tồn tại trên đĩa không.                                    |
| **`is_file()`**     | `is_file(string $path): bool`        | Kiểm tra đường dẫn có thực sự là một file thông thường hay không.                            |
| **`is_dir()`**      | `is_dir(string $path): bool`         | Kiểm tra đường dẫn có phải là một thư mục (directory) hay không.                             |
| **`is_readable()`** | `is_readable(string $path): bool`    | Kiểm tra xem PHP có quyền đọc file/thư mục này không.                                        |
| **`is_writable()`** | `is_writable(string $path): bool`    | Kiểm tra xem PHP có quyền ghi đè / ghi tiếp vào file/thư mục không.                          |
| **`filesize()`**    | `filesize(string $path): int\|false` | Lấy kích thước file tính theo đơn vị **Byte**.                                               |
| **`unlink()`**      | `unlink(string $path): bool`         | **Xóa hoàn toàn một file** khỏi ổ cứng (thường dùng để dọn dẹp ảnh đại diện cũ khi sửa/xóa). |

---

## 3. Các Phương Pháp ĐỌC File Trong PHP

### 3.1. Phương Pháp 1: Hàm `file()` (Đọc Từng Dòng Vào Mảng) ⭐

Hàm `file()` là lựa chọn tối ưu và tiện lợi nhất để đọc các file có cấu trúc theo từng dòng:

```php
array file(string $filename, int $flags = 0, ?resource $context = null)
```

#### Phân Tích Các Cờ (Flags) Cốt Lõi:

1. **`FILE_IGNORE_NEW_LINES`**: Loại bỏ ký tự xuống dòng (`\r`, `\n`) ở cuối mỗi phần tử mảng. Nếu thiếu cờ này, `$lines[0]` sẽ chứa chuỗi `"Pham Minh\r\n"`, dẫn đến việc so sánh chuỗi hoặc hiển thị bị lỗi định dạng.
2. **`FILE_SKIP_EMPTY_LINES`**: Tự động bỏ qua các dòng trống (dòng rỗng không có ký tự).

#### Thuật Toán Đọc Gom Nhóm Bản Ghi (Bước Nhảy `+5`):

```php
$filePath = __DIR__ . '/../student.txt';
$students = [];

if (file_exists($filePath)) {
    // Đọc tất cả các dòng hợp lệ vào mảng 1 chiều
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $total = count($lines);

    // Duyệt mảng với bước nhảy $i += 5 để gom nhóm 5 dòng liên tiếp thành 1 sinh viên
    for ($i = 0; $i < $total; $i += 5) {
        $students[] = [
            'name'     => $lines[$i] ?? '',
            'birthday' => $lines[$i + 1] ?? '',
            'address'  => $lines[$i + 2] ?? '',
            'image'    => $lines[$i + 3] ?? 'default.png',
            'class'    => $lines[$i + 4] ?? ''
        ];
    }
}
```

---

### 3.2. Phương Pháp 2: Hàm `file_get_contents()` (Đọc Toàn Bộ File Thành Chuỗi)

- **Cú pháp:** `string|false file_get_contents(string $filename)`
- **Đặc điểm:** Đọc toàn bộ nội dung của file vào một biến chuỗi (string) duy nhất trong bộ nhớ RAM.
- **Ứng dụng:** Đọc file JSON (`json_decode(file_get_contents('data.json'), true)`), đọc file HTML mẫu, đọc dữ liệu API từ URL từ xa.

---

### 3.3. Phương Pháp 3: Con Trỏ File Stream Truyền Thống (`fopen` + `fgets` + `feof` + `fclose`)

Phương pháp này mở một luồng dữ liệu (Stream) và đọc tuần tự từng dòng. Nó **không nạp toàn bộ file vào RAM**, rất hữu ích khi đọc các file log khổng lồ hàng trăm Megabyte hoặc Gigabyte:

```php
$filePath = __DIR__ . '/../student.txt';
$students = [];

if (file_exists($filePath)) {
    $handle = fopen($filePath, "r"); // Mở file ở chế độ 'r' (Read)

    if ($handle) {
        // feof (File End-Of-File): Kiểm tra con trỏ đã đến cuối file chưa
        while (!feof($handle)) {
            $name     = trim((string)fgets($handle));
            $birthday = trim((string)fgets($handle));
            $address  = trim((string)fgets($handle));
            $image    = trim((string)fgets($handle));
            $class    = trim((string)fgets($handle));

            if ($name !== '') {
                $students[] = [
                    'name'     => $name,
                    'birthday' => $birthday,
                    'address'  => $address,
                    'image'    => $image ?: 'default.png',
                    'class'    => $class
                ];
            }
        }
        fclose($handle); // Bắt buộc đóng con trỏ để giải phóng tài nguyên
    }
}
```

---

## 4. Các Phương Pháp GHI File Trong PHP

### 4.1. Phương Pháp 1: Hàm `file_put_contents()` (Ghi Nhanh & An Toàn) ⭐

```php
int|false file_put_contents(string $filename, mixed $data, int $flags = 0, ?resource $context = null)
```

#### Phân Tích Các Cờ (Flags) Quan Trọng:

1. **`FILE_APPEND`**: **Ghi nối tiếp vào cuối file**. Nếu không có cờ này, hàm sẽ **xóa sạch toàn bộ nội dung cũ** và ghi đè nội dung mới từ đầu.
2. **`LOCK_EX`**: **Khóa độc quyền (Exclusive Lock)**. Đảm bảo trong khoảnh khắc file đang được ghi, không có tiến trình/người dùng nào khác được phép ghi cùng lúc, ngăn ngừa tuyệt đối lỗi hỏng dữ liệu (Data Corruption).

#### Code Mẫu Thêm Mới Sinh Viên Vào Cuối File:

```php
$filePath = __DIR__ . '/../student.txt';

// 1. Chuẩn bị nội dung 5 dòng kết thúc bằng ký tự ngắt dòng chuẩn
$content = $name . PHP_EOL
         . $birthday . PHP_EOL
         . $address . PHP_EOL
         . $image . PHP_EOL
         . $class . PHP_EOL;

// 2. Thực hiện ghi nối tiếp vào cuối file kèm khóa an toàn
$bytes = file_put_contents($filePath, $content, FILE_APPEND | LOCK_EX);

if ($bytes !== false) {
    echo "Đã lưu thành công {$bytes} bytes vào file!";
}
```

---

### 4.2. Phương Pháp 2: Con Trỏ File Ghi Tiếp (`fopen` với Mode `'a'` + `fwrite` + `fclose`)

```php
$filePath = __DIR__ . '/../student.txt';
$handle = fopen($filePath, "a"); // Mode 'a' = Append (Ghi tiếp vào cuối)

if ($handle) {
    if (flock($handle, LOCK_EX)) { // Khóa file trước khi ghi
        fwrite($handle, $name . PHP_EOL);
        fwrite($handle, $birthday . PHP_EOL);
        fwrite($handle, $address . PHP_EOL);
        fwrite($handle, $image . PHP_EOL);
        fwrite($handle, $class . PHP_EOL);
        flock($handle, LOCK_UN); // Mở khóa sau khi ghi xong
    }
    fclose($handle); // Đóng file
}
```

---

## 5. Bảng Tra Cứu Toàn Bộ Các Chế Độ Mở File (`fopen` Modes)

| Mode       | Mục Đích                | Vị Trí Con Trỏ Ban Đầu | Nếu File **ĐÃ TỒN TẠI**                   | Nếu File **CHƯA TỒN TẠI**       |
| :--------- | :---------------------- | :--------------------- | :---------------------------------------- | :------------------------------ |
| **`'r'`**  | **Chỉ Đọc** (Read)      | Đầu file               | Giữ nguyên nội dung                       | **Phát cảnh báo lỗi `Warning`** |
| **`'r+'`** | **Đọc và Ghi**          | Đầu file               | Giữ nguyên nội dung, ghi đè từ đầu        | **Phát cảnh báo lỗi `Warning`** |
| **`'w'`**  | **Chỉ Ghi** (Write)     | Đầu file               | **XÓA SẠCH TOÀN BỘ NỘI DUNG VỀ 0 BYTE**   | Tự động tạo file mới            |
| **`'w+'`** | **Đọc và Ghi đè**       | Đầu file               | **XÓA SẠCH TOÀN BỘ NỘI DUNG VỀ 0 BYTE**   | Tự động tạo file mới            |
| **`'a'`**  | **Ghi tiếp** (Append)   | **Cuối file**          | Giữ nguyên nội dung cũ, ghi nối tiếp      | Tự động tạo file mới            |
| **`'a+'`** | **Đọc và Ghi tiếp**     | Cuối file khi ghi      | Giữ nguyên nội dung cũ                    | Tự động tạo file mới            |
| **`'x'`**  | **Tạo mới và Ghi**      | Đầu file               | **Phát lỗi `Warning` (Chỉ tạo file mới)** | Tự động tạo file mới            |
| **`'x+'`** | **Tạo mới, Đọc và Ghi** | Đầu file               | **Phát lỗi `Warning`**                    | Tự động tạo file mới            |

---

## 6. Kiến Trúc Luồng Dữ Liệu CRUD Toàn Diện Trong Ứng Dụng Web (Data Flow)

Trong kiến trúc ứng dụng Web hướng dữ liệu (CRUD Application), luồng dữ liệu (Data Flow) giữa các chức năng được mô hình hóa rõ ràng như sau:

```mermaid
sequenceDiagram
    autonumber
    actor User as Người Dùng
    participant Router as index.php
    participant DAO as studentHelper.php
    participant Storage as student.txt & uploads/

    Note over User,Storage: 1. LIST: Đọc toàn bộ danh sách
    User->>Router: GET index.php?page=list
    Router->>DAO: getAllStudents()
    DAO->>Storage: file('student.txt') gom nhóm 5 dòng
    Storage-->>DAO: Mảng $students
    DAO-->>Router: Trả về dữ liệu
    Router-->>User: Hiển thị bảng danh sách kèm ảnh đại diện

    Note over User,Storage: 2. ADD: Thêm sinh viên mới + Upload ảnh
    User->>Router: POST index.php?page=add (Form data + File)
    Router->>Storage: move_uploaded_file() vào uploads/
    Router->>DAO: addStudent($studentData)
    DAO->>Storage: file_put_contents(student.txt, FILE_APPEND | LOCK_EX)
    DAO-->>Router: Thành công
    Router-->>User: Chuyển hướng về trang danh sách (PRG Pattern)

    Note over User,Storage: 3. DETAIL: Xem chi tiết 1 sinh viên
    User->>Router: GET index.php?page=detail&id=0
    Router->>DAO: getStudentByIndex(0)
    DAO-->>Router: Trả về $student[0]
    Router-->>User: Hiển thị hồ sơ chi tiết (Profile Card)

    Note over User,Storage: 4. EDIT: Cập nhật thông tin & Thay đổi ảnh
    User->>Router: GET index.php?page=edit&id=0 (Nạp dữ liệu cũ vào Form)
    User->>Router: POST index.php?page=edit&id=0 (Submit dữ liệu mới)
    Router->>Storage: Upload ảnh mới -> unlink() xóa ảnh cũ
    Router->>DAO: updateStudent(0, $newData)
    DAO->>Storage: saveAllStudents() ghi đè toàn bộ file
    DAO-->>Router: Thành công
    Router-->>User: Cập nhật thành công!

    Note over User,Storage: 5. DELETE: Xóa sinh viên & Dọn dẹp ảnh
    User->>Router: GET index.php?page=delete&id=0
    Router->>DAO: deleteStudent(0)
    DAO->>Storage: unlink('uploads/' . $img) -> unset($students[0]) -> Ghi đè file
    DAO-->>Router: Xóa thành công
    Router-->>User: Chuyển hướng về index.php?page=list
```

---

## 7. Đóng Gói Bộ Thư Viện Thao Tác Dữ Liệu (`studentHelper.php`)

Đây là mô hình chuẩn mực áp dụng mẫu thiết kế **Data Access Object (DAO)** trong PHP thuần:

```php
<?php
function getStudentFilePath(): string {
    return __DIR__ . '/../student.txt';
}

function getAllStudents(): array {
    $filePath = getStudentFilePath();
    if (!file_exists($filePath)) return [];

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $students = [];
    $total = count($lines);

    for ($i = 0; $i < $total; $i += 5) {
        $students[] = [
            'name'     => $lines[$i] ?? '',
            'birthday' => $lines[$i + 1] ?? '',
            'address'  => $lines[$i + 2] ?? '',
            'image'    => $lines[$i + 3] ?? 'default.png',
            'class'    => $lines[$i + 4] ?? ''
        ];
    }
    return $students;
}

function getStudentByIndex(int $index): ?array {
    $students = getAllStudents();
    return $students[$index] ?? null;
}

function saveAllStudents(array $students): bool {
    $filePath = getStudentFilePath();
    $content = '';

    foreach ($students as $sv) {
        $content .= trim((string)$sv['name']) . PHP_EOL
                  . trim((string)$sv['birthday']) . PHP_EOL
                  . trim((string)$sv['address']) . PHP_EOL
                  . trim((string)$sv['image']) . PHP_EOL
                  . trim((string)$sv['class']) . PHP_EOL;
    }

    return file_put_contents($filePath, $content, LOCK_EX) !== false;
}

function addStudent(array $studentData): bool {
    $filePath = getStudentFilePath();
    $content = trim((string)($studentData['name'] ?? '')) . PHP_EOL
             . trim((string)($studentData['birthday'] ?? '')) . PHP_EOL
             . trim((string)($studentData['address'] ?? '')) . PHP_EOL
             . trim((string)($studentData['image'] ?? 'default.png')) . PHP_EOL
             . trim((string)($studentData['class'] ?? '')) . PHP_EOL;

    return file_put_contents($filePath, $content, FILE_APPEND | LOCK_EX) !== false;
}

function updateStudent(int $index, array $newStudentData): bool {
    $students = getAllStudents();
    if (!isset($students[$index])) return false;

    $students[$index] = $newStudentData;
    return saveAllStudents($students);
}

function deleteStudent(int $index): bool {
    $students = getAllStudents();
    if (!isset($students[$index])) return false;

    // 1. Dọn dẹp ảnh đại diện cũ trong uploads/
    $imageFile = $students[$index]['image'] ?? '';
    if ($imageFile && $imageFile !== 'default.png') {
        $imagePath = __DIR__ . '/../uploads/' . $imageFile;
        if (file_exists($imagePath) && is_file($imagePath)) {
            @unlink($imagePath);
        }
    }

    // 2. Xóa và đánh lại chỉ số mảng
    unset($students[$index]);
    $students = array_values($students);

    return saveAllStudents($students);
}
```
