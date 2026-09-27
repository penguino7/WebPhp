# CHUYÊN ĐỀ 1: TEMPLATE, LAYOUT VÀ ĐIỀU HƯỚNG ROUTER (BÀI 1 & BÀI 2)

---

## 1. Cấu trúc Layout Website dạng Modular

### 1.1. Tại sao cần chia nhỏ Template?
Trong phát triển web truyền thống, nếu mỗi trang HTML đều lặp lại thanh Header, Menu bên trái và Footer thì:
* Khi cần sửa một đường link trong Menu, ta phải mở hàng chục file để sửa $\rightarrow$ Rất dễ sai sót và mất thời gian.
* **Giải pháp trong PHP:** Tách các phần giao diện dùng chung thành các file nhỏ:
  * `Head.php`: Chứa thẻ `<head>`, link CSS/JS chung, banner đầu trang.
  * `Menu.php`: Chứa thanh danh mục bên trái (`<aside class="left-menu">`).
  * `Footer.php`: Chứa bản quyền, banner chân trang (`<footer>`).

---

## 2. Các hàm nhúng file trong PHP

PHP cung cấp 4 hàm nhúng file với sự khác biệt rõ rệt:

| Hàm | Khi file nhúng **BỊ LỖI / KHÔNG TÌM THẤY** | Số lần nhúng |
| :--- | :--- | :--- |
| **`include`** | Báo `Warning`, **vẫn tiếp tục chạy** code phía dưới | Nhúng nhiều lần |
| **`include_once`** | Báo `Warning`, **vẫn tiếp tục chạy** | Chỉ nhúng **1 lần duy nhất** (tránh trùng lặp hàm/class) |
| **`require`** | Báo `Fatal Error`, **dừng toàn bộ chương trình ngay lập tức** | Nhúng nhiều lần |
| **`require_once`** | Báo `Fatal Error`, **dừng chương trình ngay lập tức** | Chỉ nhúng **1 lần duy nhất** |

### 💡 Quy tắc chọn hàm:
* Dùng `require` / `require_once` cho: **File thư viện hàm, cấu hình CSDL, file cốt lõi** (nếu thiếu là app không chạy được).
* Dùng `include` / `include_once` cho: **Header, Footer, Giao diện HTML phụ trợ**.
* Dùng `__DIR__`: Luôn sử dụng hằng số `__DIR__` (trả về đường dẫn thư mục hiện tại) để đường dẫn file luôn chính xác 100%:
  ```php
  include_once __DIR__ . '/../Bai1/Head.php';
  ```

---

## 3. Mô hình Single Entry Point (Trang cửa sổ đơn `index.php`)

Thay vì mỗi chức năng tạo một file `.php` riêng lẻ truy cập trực tiếp (`a.php`, `b.php`), ta dùng **1 file `index.php` làm cửa ngõ duy nhất** và nhận tham số `$_GET['page']`:

```php
<?php
// 1. Lấy tham số page từ URL (mặc định vào trang 'home')
$page = $_GET['page'] ?? 'home';

// 2. Nhúng khung giao diện chung
include_once __DIR__ . '/../Bai1/Head.php';
include_once __DIR__ . '/../Bai1/Menu.php';
?>

<!-- 3. Khu vực nội dung chính thay đổi linh hoạt -->
<div class="main-content">
    <div class="menu-nav">
        <a href="index.php?page=home" class="<?= ($page == 'home') ? 'active' : '' ?>">Home</a>
        <a href="index.php?page=feature1" class="<?= ($page == 'feature1') ? 'active' : '' ?>">Chức năng 1</a>
    </div>

    <div class="content">
        <?php
        switch ($page) {
            case 'feature1':
                include __DIR__ . '/pages/feature1.php';
                break;
            case 'home':
            default:
                include __DIR__ . '/pages/home.php';
                break;
        }
        ?>
    </div>
</div>

<?php
// 4. Nhúng chân trang
include_once __DIR__ . '/../Bai1/Footer.php';
?>
```

### 🎯 Ưu điểm của Single Entry Point:
1. Giao diện (Head, Menu, Footer) chỉ cần load 1 lần, không bị nháy trang.
2. Dễ dàng kiểm soát bảo mật, phân quyền người dùng tại 1 điểm duy nhất trước khi include các trang con.
