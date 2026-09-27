# CHUYÊN ĐỀ 2: TRUYỀN NHẬN DỮ LIỆU & XỬ LÝ FORM (BÀI 3 & BÀI 4)

---

## 1. So sánh phương thức GET và POST

| Tiêu chí | Phương thức GET (`$_GET`) | Phương thức POST (`$_POST`) |
| :--- | :--- | :--- |
| **Vị trí gửi dữ liệu** | Gắn trực tiếp trên thanh URL (`?page=home&id=5`) | Đóng gói trong phần Body của HTTP Request |
| **Bảo mật** | Thấp (lộ mật khẩu, thông tin nhạy cảm trên URL) | Cao hơn (không hiển thị dữ liệu trên URL) |
| **Dung lượng gửi** | Bị giới hạn bởi độ dài URL (khoảng 2048 ký tự) | Không giới hạn lý thuyết (cấu hình trong `php.ini`) |
| **Khả năng Bookmark/Share** | Dễ dàng chia sẻ link tìm kiếm, phân trang | Không chia sẻ được link chứa kết quả submit |
| **Upload file** | **KHÔNG THỂ** gửi file nhị phân | **BẮT BUỘC** dùng POST |
| **Khi nào sử dụng?** | Tìm kiếm, lọc sản phẩm, phân trang, menu tab | Đăng ký, đăng nhập, thanh toán, upload file, thêm sửa xóa dữ liệu |

---

## 2. Kỹ thuật Chuẩn hóa và Bảo mật dữ liệu Form

Khi nhận dữ liệu từ người dùng qua `$_POST` hoặc `$_GET`, **TUYỆT ĐỐI KHÔNG TIN TƯỞNG DỮ LIỆU ĐẦU VÀO**:

```php
// 1. Cắt bỏ khoảng trắng thừa đầu và cuối
$username = trim($_POST['username'] ?? '');

// 2. Chống tấn công XSS (Cross-Site Scripting) khi in ra HTML
echo htmlspecialchars($username);

// 3. Ép kiểu dữ liệu số
$age = intval($_POST['age'] ?? 0);
$price = floatval($_POST['price'] ?? 0);
```

### 💡 Kỹ thuật Sticky Form (Giữ lại dữ liệu đã nhập):
Giúp người dùng không phải gõ lại từ đầu nếu form bị báo lỗi:
```html
<input 
    type="text" 
    name="username" 
    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
>
```

---

## 3. Upload File trong PHP (`$_FILES`)

### 3.1. Yêu cầu bắt buộc của thẻ Form:
Khi form có `<input type="file">`, form **BẮT BUỘC** phải có 2 thuộc tính:
```html
<form method="POST" action="upload.php" enctype="multipart/form-data">
    <input type="file" name="avatar">
    <button type="submit">Tải lên</button>
</form>
```

### 3.2. Cấu trúc mảng `$_FILES['avatar']`:
* `$_FILES['avatar']['name']`: Tên gốc của file (vd: `anh_the.jpg`).
* `$_FILES['avatar']['type']`: Định dạng MIME của file (vd: `image/jpeg`).
* `$_FILES['avatar']['tmp_name']`: Đường dẫn tạm thời của file trên máy chủ do PHP lưu tạm.
* `$_FILES['avatar']['error']`: Mã lỗi upload (`0` = `UPLOAD_ERR_OK` - thành công).
* `$_FILES['avatar']['size']`: Kích thước file (tính bằng byte).

### 3.3. Hàm di chuyển file từ thư mục tạm vào thư mục lưu trữ:
```php
$targetDir = __DIR__ . '/../uploads/';
$targetFile = $targetDir . basename($_FILES['avatar']['name']);

if ($_FILES['avatar']['error'] === 0) {
    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
        echo "Upload file thành công!";
    } else {
        echo "Lỗi khi di chuyển file vào thư mục uploads!";
    }
}
```

---

## 4. Các bài toán thực hành tiêu biểu (Bài 3 & Bài 4)
* **Vẽ bảng động (`DrawTable`):** Nhận số dòng và số cột $\rightarrow$ dùng 2 vòng lặp `for` lồng nhau sinh thẻ `<tr>` và `<td>`.
* **Máy tính bỏ túi (`Calculate`):** Nhận 2 số $a, b$ và phép tính ($+, -, \times, \div$) $\rightarrow$ kiểm tra trường hợp chia cho 0 trước khi tính.
* **Xử lý mảng số (`Array1`, `Array2`):** Nhập dãy số cách nhau bởi dấu phẩy $\rightarrow$ dùng `explode(',', $str)` tách thành mảng.
