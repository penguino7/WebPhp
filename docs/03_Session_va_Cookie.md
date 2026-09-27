# Quản Lý Trạng Thái Người Dùng: Session & Cookie Trong PHP

---

## 1. Vấn Đề "Stateless" Của Giao Thức HTTP & Nhu Cầu Quản Lý Trạng Thái

Giao thức HTTP là giao thức **phi trạng thái (Stateless Protocol)**:
* Máy chủ (Server) xử lý mỗi HTTP Request của người dùng một cách hoàn toàn độc lập và tách biệt.
* Sau khi Server gửi phản hồi (Response) về trình duyệt, kết nối mạng sẽ **ngắt ngay lập tức**. Server không lưu giữ bất kỳ thông tin nhận diện nào về người dùng đó trong bộ nhớ.
* Khi người dùng bấm sang trang thứ 2, Server xem đó là một người lạ hoàn toàn mới.

👉 **Giải pháp:** Để lưu giữ trạng thái đăng nhập, giỏ hàng, thông tin phiên làm việc, PHP cung cấp 2 giải pháp song hành: **Session** (Quản lý tại Máy chủ) và **Cookie** (Lưu trữ tại Trình duyệt).

---

## 2. Bảng So Sánh Toàn Diện Giữa Session Và Cookie

| Tiêu chí | Session (`$_SESSION`) | Cookie (`$_COOKIE`) |
| :--- | :--- | :--- |
| **Nơi lưu trữ dữ liệu thực tế** | **Server-side** (Tập tin tạm `sess_<id>` trên ổ cứng hoặc Redis/Memcached của máy chủ). | **Client-side** (Tập tin văn bản do Trình duyệt quản lý trên máy tính người dùng). |
| **Tính bảo mật** | **Rất cao**: Dữ liệu nằm hoàn toàn trên Server. Người dùng không thể tự ý xem hoặc chỉnh sửa giá trị biến. | **Thấp hơn**: Người dùng có thể bấm F12 xem, sửa hoặc xóa dữ liệu cookie tùy ý. |
| **Dung lượng lưu trữ** | Lớn (phụ thuộc vào RAM và dung lượng ổ cứng của máy chủ). | Giới hạn (tối đa **4KB** cho mỗi cookie, tối đa khoảng 20-50 cookie/domain). |
| **Vòng đời (Lifetime)** | Tự động hủy khi **đóng trình duyệt** (hoặc sau khoảng thời gian không hoạt động `session.gc_maxlifetime`). | Tồn tại lâu dài theo mốc thời gian hết hạn (`expires`) cụ thể do lập trình viên thiết lập (vài ngày, vài tháng, vài năm). |
| **Cách thức liên kết** | Server gửi 1 mã định danh ngẫu nhiên (**`PHPSESSID`**) lưu tạm vào cookie của Client để đối chiếu. | Trình duyệt tự động đính kèm toàn bộ cookie vào Header `Cookie` của mọi HTTP Request gửi lên server. |
| **Ứng dụng thực tế** | Xác thực Đăng nhập/Đăng xuất, Phân quyền Quản trị (Admin Guard), Giỏ hàng điện tử, Mã CAPTCHA. | Ghi nhớ tài khoản ("Remember Me"), Lưu Theme Sáng/Tối, Lịch sử truy cập gần nhất, Theo dõi hành vi (Tracking). |

---

## 3. Quản Lý Phiên Làm Việc (Session) Trong PHP

### 3.1. Cơ Chế Hoạt Động Cốt Lõi Của Session (Sequence Diagram)

```mermaid
sequenceDiagram
    autonumber
    actor User as Trình duyệt (Client)
    participant Server as Web Server (PHP Engine)
    participant Storage as Ổ cứng Server (Session Storage)

    User->>Server: 1. POST /login.php (username="admin", password="123")
    Note over Server: Gọi session_start()<br/>PHP sinh ngẫu nhiên 1 chuỗi Session ID duy nhất (ví dụ: sess_abc123)
    Server->>Storage: Tạo file lưu trữ sess_abc123 trên ổ cứng
    Note over Server: Gán $_SESSION['user'] = 'admin'<br/>$_SESSION['role'] = 'admin'
    Server->>Storage: Tuần tự hóa (Serialize) dữ liệu $_SESSION ghi vào file sess_abc123
    Server-->>User: 2. Phản hồi Response kèm HTTP Header: Set-Cookie: PHPSESSID=abc123; path=/; HttpOnly
    Note over User: Trình duyệt lưu Cookie PHPSESSID=abc123 vào bộ nhớ

    User->>Server: 3. Truy cập trang quản trị GET /admin.php (Kèm Header Cookie: PHPSESSID=abc123)
    Note over Server: Gọi session_start()<br/>PHP đọc Cookie PHPSESSID -> thấy giá trị "abc123"
    Server->>Storage: Tìm và đọc file sess_abc123
    Storage-->>Server: Giải tuần tự hóa (Unserialize) dữ liệu vào mảng $_SESSION
    Note over Server: Kiểm tra if ($_SESSION['role'] === 'admin') -> Cho phép truy cập!
    Server-->>User: 4. Trả về giao diện Quản Trị Viên thành công
```

---

### 3.2. Hàm `session_start()` & Quy Tắc Vàng
* **Quy tắc bắt buộc:** `session_start()` phải được gọi **trước khi xuất bất kỳ ký tự HTML, thẻ `<DOCTYPE>`, hay thậm chí là 1 dấu cách/dòng trống** ra màn hình. Nếu vi phạm, PHP sẽ báo lỗi:
  > `Warning: session_start(): Cannot start session when headers already sent by ...`
* **Kiểm tra trạng thái trước khi khởi tạo:**
  ```php
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  ```

---

### 3.3. Các Hàm Quản Lý Session Chuyên Sâu

#### A. Hàm `session_regenerate_id(bool $delete_old_session = false): bool`
* **Bảo mật tối cao:** Khi người dùng chuyển trạng thái từ "Khách vãng lai" sang "Đã đăng nhập", **BẮT BUỘC** phải gọi hàm này.
* **Cơ chế:** Nó sẽ tạo ra một `PHPSESSID` hoàn toàn mới và hủy Session ID cũ, **ngăn chặn 100% cuộc tấn công cố định phiên (Session Fixation Attack)**.
* **Code mẫu khi đăng nhập thành công:**
  ```php
  if ($loginSuccess) {
      session_regenerate_id(true); // true = xóa file session cũ
      $_SESSION['logged_in'] = true;
      $_SESSION['username'] = $username;
  }
  ```

#### B. Quy Trình 4 Bước Đăng Xuất & Hủy Session Hoàn Toàn:
Để đăng xuất triệt để và an toàn không để lại dấu vết trong bộ nhớ:
```php
<?php
// Bước 1: Khởi động session để có quyền thao tác
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bước 2: Xóa sạch toàn bộ biến trong mảng $_SESSION trong bộ nhớ RAM
$_SESSION = [];
session_unset();

// Bước 3: Hủy bỏ Cookie PHPSESSID trên trình duyệt của người dùng
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// Bước 4: Hủy hoàn toàn file session lưu trữ trên ổ cứng máy chủ
session_destroy();

// Chuyển hướng người dùng về trang Login
header("Location: index.php?page=login");
exit();
```

---

### 3.4. Xây Dựng Bộ Lọc Phân Quyền (Auth Guard Middleware)

Tạo file [`auth.php`](file:///c:/xampp/htdocs/Bai5/auth.php) và nhúng vào tất cả các trang quản trị nội bộ:
```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra xem session đã đăng nhập và có quyền hợp lệ chưa
if (!isset($_SESSION['admin_user']) || empty($_SESSION['admin_user'])) {
    // Nếu chưa đăng nhập, lập tức chuyển hướng về trang Login kèm thông báo lỗi
    header("Location: /Bai5/index.php?page=login&error=unauthorized");
    exit(); // Luôn luôn gọi exit() sau header Location để ngăn chặn chạy code phía dưới
}
```

---

## 4. Quản Lý Cookie Trong PHP

### 4.1. Phân Tích Cặn Kẽ 7 Tham Số Của Hàm `setcookie()`

```php
bool setcookie(
    string $name,
    string $value = "",
    int $expires_or_options = 0,
    string $path = "",
    string $domain = "",
    bool $secure = false,
    bool $httponly = false
)
```

1. **`$name`** *(Bắt buộc)*: Tên định danh của Cookie (ví dụ: `'remember_user'`, `'theme'`).
2. **`$value`**: Giá trị của cookie (luôn ở dạng chuỗi văn bản).
3. **`$expires_or_options`**: Thời điểm hết hạn tính bằng **Timestamp giây Unix**:
   * Truyền `0` (mặc định): **Session Cookie** (tự biến mất khi đóng trình duyệt).
   * Lưu 30 ngày: `time() + (30 * 24 * 3600)`.
4. **`$path`**: Phạm vi thư mục trên server mà cookie có hiệu lực:
   * Truyền `'/'`: Cookie có hiệu lực trên **toàn bộ website**.
   * Truyền `'/admin'`: Chỉ các file trong thư mục `/admin` mới đọc được cookie này.
5. **`$domain`**: Tên miền cookie có hiệu lực (ví dụ `.example.com` cho phép tất cả các subdomain `api.example.com`, `shop.example.com` cùng đọc).
6. **`$secure`**:
   * `false` (mặc định): Gửi qua cả HTTP và HTTPS.
   * `true`: **Chỉ gửi cookie khi kết nối mạng được mã hóa HTTPS**.
7. **`$httponly`**:
   * `false` (mặc định): Mã JavaScript có thể đọc được cookie qua `document.cookie`.
   * `true` (**Khuyến nghị bảo mật bắt buộc cho Cookie nhạy cảm**): **Cấm hoàn toàn JavaScript truy cập cookie**. Giúp triệt tiêu nguy cơ hacker dùng tấn công XSS để đánh cắp Token/Session của người dùng!

---

### 4.2. Cơ Chế Xóa Cookie Bằng Timestamp Quá Khứ
Vì Cookie lưu trữ trên máy người dùng, Server không thể can thiệp xóa trực tiếp file của client. Server sẽ gửi chỉ thị `setcookie` với thời gian hết hạn **lùi về quá khứ**:

```php
// Đặt thời gian hết hạn lùi về 1 giờ trước (time() - 3600)
setcookie('remember_user', '', time() - 3600, '/');
```
Khi trình duyệt nhận được lệnh này, nó thấy mốc thời gian đã trôi qua nên sẽ tự động xóa cookie khỏi bộ nhớ.

---

### 4.3. Lưu Trữ Dữ Liệu Có Cấu Trúc (Mảng/Đối Tượng) Vào Cookie Qua JSON

Vì Cookie chỉ chấp nhận lưu chuỗi ký tự đơn, để lưu một danh sách mảng nhiều phần tử (như Danh sách liên kết yêu thích - Favourite List CRUD), ta kết hợp với `json_encode()` và `json_decode()`:

```php
// 1. Thao tác GHI MẢNG vào Cookie:
$favList = [
    ['id' => 1, 'title' => 'Google', 'url' => 'https://google.com'],
    ['id' => 2, 'title' => 'GitHub', 'url' => 'https://github.com']
];
// Chuyển mảng thành chuỗi JSON: '[{"id":1,"title":"Google"...}]'
$jsonString = json_encode($favList, JSON_UNESCAPED_UNICODE);
setcookie('favourite_links', $jsonString, time() + 30 * 24 * 3600, '/');

// 2. Thao tác ĐỌC MẢNG từ Cookie:
$cookieRaw = $_COOKIE['favourite_links'] ?? '[]';
// Tham số thứ 2 là true để decode thành Associative Array (Mảng kết hợp)
$list = json_decode($cookieRaw, true);

// 3. Duyệt và in ra màn hình:
foreach ($list as $item) {
    echo "<li><a href='{$item['url']}'>{$item['title']}</a></li>";
}
```
