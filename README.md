# WebPhp: Hệ Thống Bài Thực Hành & Tài Liệu Lập Trình PHP Toàn Diện

> **Kho lưu trữ thực hành mã nguồn mở (Hands-on Labs & Technical Documentation)** được xây dựng từ cơ bản đến nâng cao, kết hợp giữa các bài toán thực hành thực tế và bộ tài liệu phân tích chuyên sâu nhằm giúp người học hiểu rõ bản chất cơ chế hoạt động của ngôn ngữ PHP và lập trình Web hiện đại.

---

## 1. Mục Tiêu Của Dự Án

- **Hiểu sâu bản chất:** Không chỉ dừng lại ở việc viết code chạy được, dự án tập trung làm rõ cách PHP Engine tương tác với HTTP Request/Response, cách quản lý bộ nhớ RAM, Session/Cookie và luồng File Stream trên hệ điều hành.
- **Kiến trúc sạch & Chuẩn mực:** Áp dụng tư duy phân tách giao diện (Modular Template), điều hướng tập trung (Single Entry Point Router), bảo mật dữ liệu (XSS, LFI, Session Fixation), và xử lý mảng/ma trận hiệu năng cao.
- **Hệ thống hóa 2 trong 1:**
  1. **Source Code Thực Hành (`Bai1` $\rightarrow$ `Bai16`):** Mỗi bài là một đồ án mini hoặc chức năng hoàn chỉnh.
  2. **Tài Liệu Chuyên Sâu (`docs/`):** Phân tích chi tiết từng hàm, tham số, cơ chế nội bộ và biểu đồ tuần tự (Sequence Diagram).

---

## 2. Cấu Trúc Tổng Thể Thư Mục

```text
c:\xampp\htdocs\
│
├── docs/
│   ├── README.md
│   ├── 01_Template_va_Layout.md
│   ├── 02_Data_Transfer_va_Form.md
│   ├── 03_Session_va_Cookie.md
│   ├── 04_Ham_va_Mang.md
│   └── 05_Doc_Ghi_File.md
│
├── Bai1/
├── Bai2/
├── Bai3/
├── Bai4/
├── Bai5/
├── Bai6/
├── Bai7/
├── Bai8/
├── Bai9/
├── Bai10/
│
└── ... (Bai11 -> Bai16)
```

---

## 3. Lộ Trình Thực Hành (Practical Curriculum)

|        Bài        | Tên Chủ Đề                             | Nội Dung Kỹ Thuật Chính                                                                                                                                |
| :---------------: | :------------------------------------- | :----------------------------------------------------------------------------------------------------------------------------------------------------- |
| **[Bai1](Bai1/)** | **Modular Layout Template**            | Xây dựng khung giao diện phân tách `Head.php`, `Menu.php`, `Footer.php` tái sử dụng linh hoạt.                                                         |
| **[Bai2](Bai2/)** | **Áp Dụng Template & Form Cơ Bản**     | Kế thừa Layout vào trang Đăng ký thành viên (`Register.php`) và Tính toán lương nhân viên (`Calculate.php`).                                           |
| **[Bai3](Bai3/)** | **Single Entry Point Router**          | Điều hướng `index.php?page=...`, vẽ bảng HTML động theo số dòng/cột, chuẩn hóa mảng số và upload ảnh.                                                  |
| **[Bai4](Bai4/)** | **Nhận Dữ Liệu Form & Sticky Form**    | Xử lý Form với `GET`/`POST`, duy trì giá trị cũ khi submit lỗi, bảo vệ chống XSS bằng `htmlspecialchars()`.                                            |
| **[Bai5](Bai5/)** | **Xác Thực & Phân Quyền Bằng Session** | Đăng nhập/Đăng xuất bảo mật, chống Session Fixation bằng `session_regenerate_id(true)`, Auth Guard Middleware.                                         |
| **[Bai6](Bai6/)** | **Quản Lý Trạng Thái Bằng Cookie**     | Tự động điền tài khoản ("Remember Login 30 ngày"), lưu vết thời gian truy cập gần nhất, CRUD danh sách yêu thích qua JSON Cookie.                      |
| **[Bai7](Bai7/)** | **Thư Viện Hàm & Đại Số Ma Trận**      | Đóng gói `libs/xuLyMangSo.php`, `libs/xuLyMatran.php`, tính toán đường chéo chính/phụ, nhân 2 ma trận $3 \times 3$, sắp xếp mảng kết hợp.              |
| **[Bai8](Bai8/)** | **Lưu Trữ Dữ Liệu Bằng File Text**     | Đọc gom nhóm 3 dòng/bản ghi bằng `file(..., FILE_IGNORE_NEW_LINES)`, ghi nối tiếp sinh viên mới bằng `file_put_contents(..., FILE_APPEND \| LOCK_EX)`. |
| **[Bai9](Bai9/)** | **Thao Tác File & Data Flow (CRUD)**   | Quản lý sinh viên toàn diện: List, Add, Edit, Detail, Delete, Upload ảnh đại diện và lưu trữ 5 dòng/bản ghi trong `student.txt`.                       |
| **[Bai10](Bai10/)** | **Website Đa Ngôn Ngữ (Multi-Lang)** | Hệ thống đa ngôn ngữ qua Session và gói từ điển Hằng số (`lang/vietnamese.php`, `lang/english.php`).                                                  |
|    **Bai11+**     | **Nâng Cao & Tích Hợp Đầy Đủ**         | Kết nối & Truy vấn CSDL MySQL, Giỏ hàng Web bán laptop, Tích hợp Richtext box.                                                                        |

---

## 4. Tài Liệu Phân Tích & Tra Cứu Chuyên Sâu (`docs/`)

Mỗi tài liệu trong thư mục [`docs/`](docs/) được thiết kế chi tiết, kèm theo **Sequence Diagram (Biểu đồ tuần tự Mermaid)** mô tả rõ ràng dòng dữ liệu:

1. **[01. Kiến Trúc Modular, Layout & Router](docs/01_Template_va_Layout.md)**
   - So sánh bản chất `include`, `include_once`, `require`, `require_once`.
   - Cơ chế Hash Table lookup của hậu tố `_once` và tầm quan trọng của `__DIR__`.
   - Cấu trúc Router Single Entry Point và danh sách trắng (Whitelist) phòng chống lỗ hổng LFI (Local File Inclusion).

2. **[02. Truyền Nhận Dữ Liệu & Xử Lý Form](docs/02_Data_Transfer_va_Form.md)**
   - Cơ chế ánh xạ thẻ HTML sang biến siêu toàn cục `$_POST` / `$_GET` thông qua thuộc tính `name="..."`.
   - Phân biệt chi tiết cơ chế nhận dữ liệu: Radio (`checked`), Checkbox mảng `name="skills[]"` (`checked`), Single/Multiple Select Dropdown (`selected`), Ma trận `matrix[i][j]`.
   - Sinh thẻ `<option>` tự động bằng `foreach`, so sánh `GET` vs `POST`, hàm `htmlspecialchars()`, `trim()`, và xử lý mảng `$_FILES`.

3. **[03. Quản Lý Trạng Thái: Session & Cookie](docs/03_Session_va_Cookie.md)**
   - Vấn đề Stateless của HTTP và cơ chế định danh `PHPSESSID`.
   - So sánh chuyên sâu Server-side (Session) vs Client-side (Cookie).
   - Cơ chế bảo vệ Admin Guard, 4 bước hủy Session an toàn, 7 tham số của `setcookie()`, cờ `httponly` và JSON Cookie CRUD.

4. **[04. Thư Viện Hàm & Cấu Trúc Dữ Liệu Mảng](docs/04_Ham_va_Mang.md)**
   - Khai báo kiểu dữ liệu tham số (Type Hinting) & Kiểu trả về (Return Type), cơ chế Tham trị (By Value) vs Tham chiếu (By Reference `&`).
   - Tổng hợp toàn bộ nhóm hàm mảng 1D: `count`, `min`, `max`, `array_sum`, `explode`, `implode`, `array_map`, `sort`, `rsort`.
   - Thuật toán ma trận 2D: Đường chéo chính ($i = j$), Đường chéo phụ ($j = n - 1 - i$), Chuyển vị, Nhân 2 ma trận ($A \times B$).
   - Mảng kết hợp: `array_key_exists` vs `isset` vs `in_array`, và 4 hàm sắp xếp cốt lõi (`ksort`, `krsort`, `asort`, `arsort`).

5. **[05. Thao Tác Đọc & Ghi File Văn Bản](docs/05_Doc_Ghi_File.md)**
   - Luồng File Stream và ký tự ngắt dòng `PHP_EOL` (`\r\n` CRLF trên Windows vs `\n` LF trên Linux).
   - Đọc từng dòng bằng `file()` với cờ `FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES`.
   - Ghi nối tiếp an toàn bằng `file_put_contents()` với cờ `FILE_APPEND | LOCK_EX`.
   - Bảng tra cứu các Mode mở file (`r`, `w`, `a`, `x`, `r+`, `w+`, `a+`) và quy trình CRUD (Update/Delete) bản ghi trong file text.

---

## 5. Hướng Dẫn Cài Đặt & Chạy Trên Môi Trường Local

### Yêu Cầu Hệ Thống:

- Máy tính đã cài đặt **[XAMPP](https://www.apachefriends.org/)** (hoặc Laragon, WampServer).
- Phiên bản PHP khuyến nghị: **PHP 7.4** hoặc **PHP 8.x**.

### Các Bước Cài Đặt:

1. **Clone mã nguồn vào thư mục `htdocs` của XAMPP:**
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/penguino7/WebPhp.git .
   ```
2. **Khởi động dịch vụ Apache:**
   - Mở ứng dụng **XAMPP Control Panel**.
   - Bấm **Start** tại mục **Apache**.

3. **Truy cập và chạy thử các bài thực hành trên Trình duyệt:**
   - Trang Router tổng hợp: `http://localhost/Bai3/index.php`
   - Quản lý Session (Đăng nhập): `http://localhost/Bai5/login.php`
   - Quản lý Cookie (Danh sách yêu thích): `http://localhost/Bai6/favList.php`
   - Đại số Ma trận: `http://localhost/Bai7/index.php?page=matrix`
   - Quản lý Sinh viên bằng File: `http://localhost/Bai8/index.php?page=list`

---

## 6. Thông Tin & Đóng Góp

- **Repository:** [https://github.com/penguino7/WebPhp](https://github.com/penguino7/WebPhp)
- **Mục đích:** Nghiên cứu, học tập, và phát triển kỹ năng lập trình web backend với PHP thuần (Vanilla PHP).
