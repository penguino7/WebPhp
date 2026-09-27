# Truyền Nhận Dữ Liệu & Xử Lý Form (HTML $\rightarrow$ PHP, GET, POST & File Upload)

---

## 1. Cơ Chế Cốt Lõi: PHP Lấy Dữ Liệu Từ HTML Form Như Thế Nào?

Đây là kiến thức nền tảng và quan trọng nhất trong lập trình web với PHP: **Làm sao PHP biết người dùng đã nhập gì trên trang HTML?**

```mermaid
sequenceDiagram
    autonumber
    actor User as Người dùng (User)
    participant Browser as Trình duyệt (Browser)
    participant Server as Web Server (PHP Engine)
    participant Code as Script PHP (Xử lý)

    User->>Browser: Nhập Form (<input name="username" value="admin">) & Bấm Submit
    Browser->>Browser: Đóng gói dữ liệu theo cặp name=value (username=admin)
    Browser->>Server: Gửi HTTP Request (POST / GET) đến Web Server
    Server->>Server: Tự động phân tích gói tin & khởi tạo $_POST / $_GET
    Server->>Code: Chuyển dữ liệu cho file PHP xử lý
    Code->>Code: Lấy dữ liệu: $user = trim($_POST['username'] ?? '')
    Code-->>Server: Kết xuất HTML kết quả xử lý
    Server-->>Browser: Phản hồi trang HTML kết quả
    Browser-->>User: Hiển thị kết quả trên màn hình
```

### 1.1. Thuộc Tính `name` Trong HTML Là "Chiếc Chìa Khóa Vàng" (The Key)

- **Quy tắc bất biến:** PHP **chỉ nhận diện và lấy được dữ liệu** của một thẻ HTML nếu thẻ đó có khai báo thuộc tính **`name="..."`**.
- Tên đặt trong thuộc tính `name` ở HTML sẽ trở thành **Key (Khóa)** trong mảng `$_GET` hoặc `$_POST` của PHP.
- _Lưu ý:_ Thuộc tính `id="..."` hoặc `class="..."` chỉ dùng cho CSS và JavaScript, PHP **hoàn toàn không nhìn thấy** `id` hay `class`.

---

## 2. Chi Tiết Cách PHP Nhận Dữ Liệu Từ Từng Loại Thẻ Form HTML

### 2.1. Thẻ Nhập Văn Bản Đơn Lẻ (`text`, `password`, `hidden`, `textarea`, `number`)

#### HTML:

```html
<form method="POST" action="process.php">
  <!-- Nhập chữ -->
  <input type="text" name="username" placeholder="Tên đăng nhập" />

  <!-- Mật khẩu -->
  <input type="password" name="password" />

  <!-- Thẻ ẩn chứa cờ đánh dấu -->
  <input type="hidden" name="action" value="register" />

  <!-- Khung nhập nhiều dòng -->
  <textarea name="ghi_chu"></textarea>

  <button type="submit">Gửi</button>
</form>
```

#### PHP Nhận Dữ Liệu (`process.php`):

```php
$user   = trim($_POST['username'] ?? '');
$pass   = trim($_POST['password'] ?? '');
$action = $_POST['action'] ?? '';
$note   = trim($_POST['ghi_chu'] ?? '');
```

---

### 2.2. Nút Chọn Một (Radio Button) $\rightarrow$ Trạng Thái `checked`

- **Đặc điểm:** Các nút Radio trong cùng một câu hỏi phải có **cùng thuộc tính `name`**, nhưng **khác thuộc tính `value`**.
- **Cơ chế Submit của Trình duyệt:** Trình duyệt **chỉ gửi duy nhất** giá trị `value` của nút Radio đang được chọn (có trạng thái `checked`).
- **Kỹ thuật giữ lại lựa chọn cũ (Sticky Radio):** Dùng toán tử 3 ngôi kiểm tra giá trị cũ để in ra chữ `checked`:

#### HTML & PHP:

```php
<?php
// Lấy giá trị đã chọn (nếu chưa có thì mặc định là 'Nam')
$currentGender = $_POST['gioitinh'] ?? 'Nam';
?>

<form method="POST">
    <label>
        <input type="radio" name="gioitinh" value="Nam" <?= ($currentGender === 'Nam') ? 'checked' : '' ?>> Nam
    </label>
    <label>
        <input type="radio" name="gioitinh" value="Nu" <?= ($currentGender === 'Nu') ? 'checked' : '' ?>> Nữ
    </label>
    <label>
        <input type="radio" name="gioitinh" value="Khac" <?= ($currentGender === 'Khac') ? 'checked' : '' ?>> Khác
    </label>
    <button type="submit">Xác nhận</button>
</form>
```

---

### 2.3. Hộp Chọn Nhiều (Checkbox) $\rightarrow$ Kỹ Thuật Mảng `name="ten_bien[]"` & `checked`

- **Đặc điểm sống còn:**
  1. Nếu người dùng **không tick** vào ô Checkbox, trình duyệt **HOÀN TOÀN KHÔNG GỬI** trường đó lên server (tức là `isset($_POST['so_thich'])` sẽ trả về `false`).
  2. Để chọn được nhiều giá trị, thuộc tính `name` **BẮT BUỘC phải có cặp ngoặc vuông `[]`** ở cuối (ví dụ `name="so_thich[]"`).
- **Kỹ thuật giữ lại lựa chọn cũ (Sticky Checkbox):** Dùng hàm `in_array($val, $selectedArray)` để kiểm tra xem giá trị đó có trong mảng cũ không:

#### HTML & PHP:

```php
<?php
// Mảng danh sách các sở thích được người dùng tick chọn
$selectedHobbies = $_POST['so_thich'] ?? [];
?>

<form method="POST">
    <label>
        <input type="checkbox" name="so_thich[]" value="BongDa" <?= in_array('BongDa', $selectedHobbies) ? 'checked' : '' ?>> Đá bóng
    </label>
    <label>
        <input type="checkbox" name="so_thich[]" value="AmNhac" <?= in_array('AmNhac', $selectedHobbies) ? 'checked' : '' ?>> Âm nhạc
    </label>
    <label>
        <input type="checkbox" name="so_thich[]" value="DocSach" <?= in_array('DocSach', $selectedHobbies) ? 'checked' : '' ?>> Đọc sách
    </label>
    <button type="submit">Lưu sở thích</button>
</form>
```

---

### 2.4. Danh Sách Thả Xuống (Dropdown Select) $\rightarrow$ Trạng Thái `selected`

Khác với Radio và Checkbox dùng chữ `checked`, thẻ `<option>` bên trong thẻ `<select>` sử dụng thuộc tính **`selected`** (hoặc `selected="selected"`).

#### A. Chọn Đơn (Single Select Dropdown):

- **Cơ chế gửi dữ liệu:** Trình duyệt sẽ lấy thuộc tính `name` của thẻ `<select>` và gửi kèm `value` của thẻ `<option>` đang có thuộc tính `selected` (hoặc option mà người dùng vừa bấm chọn).
- **Lưu ý:** Nếu thẻ `<option>` không khai báo `value="..."`, trình duyệt sẽ gửi phần văn bản hiển thị bên trong `<option>Text</option>`. Tuy nhiên, chuẩn lập trình là **luôn luôn khai báo thuộc tính `value` rõ ràng**.

```php
<?php
$selectedCity = $_POST['thanh_pho'] ?? 'HN'; // Mặc định là 'HN'
?>

<form method="POST">
    <label for="thanh_pho">Chọn thành phố:</label>
    <select id="thanh_pho" name="thanh_pho">
        <option value="HN" <?= ($selectedCity === 'HN') ? 'selected' : '' ?>>Hà Nội</option>
        <option value="HCM" <?= ($selectedCity === 'HCM') ? 'selected' : '' ?>>TP. Hồ Chí Minh</option>
        <option value="DN" <?= ($selectedCity === 'DN') ? 'selected' : '' ?>>Đà Nẵng</option>
        <option value="HP" <?= ($selectedCity === 'HP') ? 'selected' : '' ?>>Hải Phòng</option>
    </select>
    <button type="submit">Lưu</button>
</form>
```

#### B. Chọn Nhiều (Multiple Select Dropdown):

- Thẻ `<select>` có thêm thuộc tính `multiple` và thuộc tính `name="skills[]"` có cặp ngoặc vuông `[]`.
- **Kỹ thuật Sticky:** Tương tự checkbox, dùng `in_array($val, $selectedSkills)` để in ra `selected`:

```php
<?php
$selectedSkills = $_POST['skills'] ?? ['PHP']; // Mặc định chọn 'PHP'
?>

<form method="POST">
    <label for="skills">Chọn các kỹ năng (giữ Ctrl để chọn nhiều):</label>
    <select id="skills" name="skills[]" multiple size="4">
        <option value="PHP" <?= in_array('PHP', $selectedSkills) ? 'selected' : '' ?>>PHP</option>
        <option value="JS" <?= in_array('JS', $selectedSkills) ? 'selected' : '' ?>>JavaScript</option>
        <option value="Python" <?= in_array('Python', $selectedSkills) ? 'selected' : '' ?>>Python</option>
        <option value="Java" <?= in_array('Java', $selectedSkills) ? 'selected' : '' ?>>Java</option>
    </select>
    <button type="submit">Lưu kỹ năng</button>
</form>

<?php
// Xử lý PHP khi nhận mảng:
if (!empty($_POST['skills'])) {
    echo "Các kỹ năng đã chọn: " . implode(', ', $_POST['skills']);
}
?>
```

#### C. Kỹ Thuật Đỉnh Cao: Dùng Vòng Lặp `foreach` Sinh Dropdown Select Tự Động

Trong thực tế dự án, ta không gõ tay từng thẻ `<option>` mà lưu dữ liệu trong mảng cấu hình và duyệt vòng lặp để sinh HTML cực kỳ sạch sẽ:

```php
<?php
// Mảng danh mục các quốc gia
$countries = [
    'VN' => 'Việt Nam',
    'US' => 'Hoa Kỳ (United States)',
    'JP' => 'Nhật Bản (Japan)',
    'KR' => 'Hàn Quốc (Korea)',
    'FR' => 'Pháp (France)'
];

$selectedCountry = $_POST['quoc_gia'] ?? 'VN';
?>

<select name="quoc_gia">
    <?php foreach ($countries as $code => $name): ?>
        <option value="<?= $code ?>" <?= ($selectedCountry === $code) ? 'selected' : '' ?>>
            <?= htmlspecialchars($name) ?>
        </option>
    <?php endforeach; ?>
</select>
```

---

### 2.5. Ma Trận & Mảng 2 Chiều Trong Form HTML

Khi cần nhập dữ liệu dạng bảng lưới (như Ma trận $3 \times 3$), ta khai báo chỉ số mảng 2 chiều trực tiếp trong `name`:

#### HTML:

```html
<input type="number" name="matrix[0][0]" value="1" />
<input type="number" name="matrix[0][1]" value="2" />
<input type="number" name="matrix[1][0]" value="3" />
<input type="number" name="matrix[1][1]" value="4" />
```

#### PHP Nhận Dữ Liệu:

PHP tự động cấu trúc thành mảng 2 chiều hoàn chỉnh:

```php
$matrix = $_POST['matrix'] ?? [];
echo $matrix[0][0]; // 1
echo $matrix[0][1]; // 2
echo $matrix[1][0]; // 3
echo $matrix[1][1]; // 4
```

---

## 3. Bản Chất Các Phương Thức Truyền Dữ Liệu: GET vs POST

| Tiêu chí                          | Phương thức GET (`$_GET`)                                                                      | Phương thức POST (`$_POST`)                                                                      |
| :-------------------------------- | :--------------------------------------------------------------------------------------------- | :----------------------------------------------------------------------------------------------- |
| **Vị trí mang dữ liệu**           | Gắn trực tiếp lên thanh URL sau dấu hỏi chấm (`?key1=val1&key2=val2`).                         | Đóng gói ngầm trong phần thân (**HTTP Request Body**).                                           |
| **Tính riêng tư / Bảo mật**       | **Thấp**: Dữ liệu hiển thị rõ trên URL, bị lưu vào lịch sử duyệt web (History) và Server Logs. | **Cao hơn**: Không lộ dữ liệu trên URL.                                                          |
| **Giới hạn dung lượng**           | Bị giới hạn bởi độ dài tối đa của URL (khoảng 2.048 ký tự tùy trình duyệt).                    | Không giới hạn về mặt lý thuyết (giới hạn thực tế cấu hình trong `php.ini` qua `post_max_size`). |
| **Gửi dữ liệu nhị phân (Binary)** | **KHÔNG THỂ**: Không gửi được file ảnh, file nén, tài liệu...                                  | **BẮT BUỘC DÙNG POST** để upload file.                                                           |
| **Khả năng Bookmark & Cache**     | Có thể lưu Bookmark, Cache kết quả và chia sẻ link trực tiếp.                                  | Không thể Bookmark kết quả submit; khi F5 sẽ hiện cảnh báo gửi lại form.                         |
| **Trường hợp áp dụng chuẩn**      | Tìm kiếm, lọc dữ liệu, phân trang danh sách, chuyển tab menu.                                  | Đăng ký, Đăng nhập, Thanh toán, Thêm/Sửa/Xóa dữ liệu, Upload file.                               |

---

## 4. Các Hàm Chuẩn Hóa & Bảo Mật Dữ Liệu Form

### 4.1. Hàm `trim()` - Cắt Khoảng Trắng Thừa

```php
$username = trim($_POST['username'] ?? ''); // "  admin  " -> "admin"
```

### 4.2. Hàm `htmlspecialchars()` - Ngăn Chặn XSS

Chuyển các ký tự HTML (`<`, `>`, `&`, `"`, `'`) thành HTML Entities để trình duyệt không thực thi mã độc JavaScript:

```php
echo htmlspecialchars($rawUserInput);
```

### 4.3. Kỹ Thuật Sticky Form (Giữ Lại Dữ Liệu Đã Nhập)

Nhúng giá trị cũ đã được lọc an toàn vào thuộc tính `value` của thẻ `<input>`:

```html
<input
  type="text"
  name="hoten"
  value="<?= htmlspecialchars($_POST['hoten'] ?? '') ?>"
/>
```

---

## 5. Cơ Chế Xử Lý Upload File Trong PHP (`$_FILES`)

### 5.1. Điều Kiện Bắt Buộc Của Form Upload

1. `method="POST"`
2. `enctype="multipart/form-data"`

```html
<form method="POST" action="uploadProcess.php" enctype="multipart/form-data">
  <input type="file" name="avatar" required />
  <button type="submit">Tải Lên</button>
</form>
```

### 5.2. Cấu Trúc Chi Tiết Mảng `$_FILES['avatar']`

- `name`: Tên gốc của file (vd: `hinh_anh.jpg`).
- `type`: Định dạng MIME (vd: `image/jpeg`).
- `tmp_name`: Đường dẫn file tạm trên Server do PHP lưu tạm.
- `error`: Mã trạng thái lỗi (`0 = UPLOAD_ERR_OK`).
- `size`: Dung lượng file tính bằng Byte.

### 5.3. Hàm `move_uploaded_file()` & Code Mẫu Chuẩn:

```php
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath   = $_FILES['avatar']['tmp_name'];
    $fileName      = $_FILES['avatar']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Đổi tên ngẫu nhiên tránh trùng lặp
    $newFileName = uniqid('img_', true) . '.' . $fileExtension;
    $destination = __DIR__ . '/../uploads/' . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destination)) {
        echo "Upload thành công: " . htmlspecialchars($newFileName);
    }
}
```
