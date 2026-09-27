# CHUYÊN ĐỀ 3: QUẢN LÝ PHIÊN (SESSION) & COOKIE (BÀI 5 & BÀI 6)

---

## 1. Bảng so sánh toàn diện giữa Session và Cookie

| Tiêu chí | Session (`$_SESSION`) | Cookie (`$_COOKIE`) |
| :--- | :--- | :--- |
| **Nơi lưu trữ** | **Server** (Máy chủ) | **Client** (Trình duyệt người dùng) |
| **Tính bảo mật** | Rất cao (Người dùng không thể tự ý sửa đổi dữ liệu) | Thấp hơn (Người dùng có thể xem/sửa cookie bằng F12) |
| **Dung lượng lưu trữ** | Lớn (phụ thuộc vào RAM server) | Giới hạn (khoảng 4KB cho mỗi cookie) |
| **Vòng đời (Lifetime)** | Mặc định tự hủy khi người dùng **đóng trình duyệt** | Tồn tại theo thời gian hết hạn do lập trình viên cấu hình |
| **Mục đích sử dụng** | Đăng nhập / Đăng xuất, Giỏ hàng, Phân quyền Admin | Nhớ tài khoản ("Remember Me"), Theme Sáng/Tối, Lịch sử truy cập |

---

## 2. Làm việc với Session trong PHP

### 2.1. Khởi động Session
Mọi trang muốn sử dụng Session **BẮT BUỘC** phải gọi hàm `session_start()` ở đầu file (trước khi xuất bất kỳ ký tự HTML nào ra màn hình):
```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```

### 2.2. Ghi và Đọc biến Session
```php
// Ghi dữ liệu vào Session khi đăng nhập thành công
$_SESSION['user'] = [
    'username' => 'admin',
    'role'     => 'admin',
    'logged_in'=> true
];

// Đọc dữ liệu từ Session
if (isset($_SESSION['user']['username'])) {
    echo "Xin chào " . $_SESSION['user']['username'];
}
```

### 2.3. Cơ chế Bảo vệ trang quản trị (Auth Guard)
Tạo file `auth.php` nhúng vào các trang nội bộ của Admin:
```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_user'])) {
    // Nếu chưa đăng nhập, chuyển hướng ngay về trang Login
    header("Location: /Bai5/index.php?page=login");
    exit();
}
```

### 2.4. Đăng xuất và Hủy Session an toàn
```php
session_start();
session_unset();     // Xóa toàn bộ biến trong $_SESSION
session_destroy();   // Hủy bỏ hoàn toàn session trên server
header("Location: index.php?page=login");
exit();
```

---

## 3. Làm việc với Cookie trong PHP

### 3.1. Cú pháp hàm `setcookie()` với 7 tham số:
```php
setcookie(
    string $name,           // 1. Tên Cookie (Bắt buộc)
    string $value = "",     // 2. Giá trị Cookie (dạng chuỗi)
    int $expires_or_options = 0, // 3. Thời điểm hết hạn (Timestamp tính bằng giây)
    string $path = "/",     // 4. Đường dẫn có hiệu lực ('/' áp dụng toàn website)
    string $domain = "",    // 5. Tên miền áp dụng (vd: .example.com)
    bool $secure = false,   // 6. true = chỉ gửi qua giao thức HTTPS
    bool $httponly = false  // 7. true = cấm JavaScript truy cập (chống XSS đánh cắp cookie)
);
```

### 3.2. Tính toán thời gian sống của Cookie:
* **Lưu Cookie tồn tại trong 30 ngày:**
  $$\text{Expire} = \text{time()} + 30 \times 24 \times 3600$$
  ```php
  setcookie('remember_user', 'ngocnhat', time() + 30 * 24 * 3600, '/');
  ```
* **HỦY / XÓA Cookie ngay lập tức:**
  Đặt thời gian hết hạn về **quá khứ** (ví dụ lùi lại 1 tiếng):
  ```php
  setcookie('remember_user', '', time() - 3600, '/');
  ```

### 3.3. Lưu trữ Mảng / Đối tượng phức tạp vào Cookie qua JSON (Bài 6 - Favourite List):
Vì Cookie chỉ lưu được kiểu chuỗi (string), để lưu 1 mảng danh sách liên kết web, ta dùng `json_encode()` và `json_decode()`:

```php
// 1. Ghi mảng vào Cookie:
$favouriteList = [
    ['title' => 'Google', 'url' => 'https://google.com'],
    ['title' => 'GitHub', 'url' => 'https://github.com']
];
setcookie('favourite_links', json_encode($favouriteList), time() + 30*24*3600, '/');

// 2. Đọc mảng từ Cookie:
$cookieRaw = $_COOKIE['favourite_links'] ?? '[]';
$list = json_decode($cookieRaw, true); // true = chuyển về mảng kết hợp PHP
```
