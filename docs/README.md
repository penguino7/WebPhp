# 📚 TỔNG HỢP KIẾN THỨC THỰC HÀNH PHP (BÀI 1 ĐẾN BÀI 8)

Tài liệu được chia thành 5 chuyên đề chuyên sâu giúp bạn ôn tập, tra cứu nhanh lý thuyết và các mẫu code cốt lõi:

---

### 📂 Danh mục các chuyên đề:

1. **[Chuyên đề 1: Template, Layout và Điều hướng Router](01_Template_va_Layout.md)** *(Bài 1 & Bài 2)*
   * Cấu trúc website dạng Modular (Head, Menu, Footer).
   * Phân biệt `include`, `include_once`, `require`, `require_once` và hằng số `__DIR__`.
   * Xây dựng Single Entry Point Router qua `index.php?page=...`.

2. **[Chuyên đề 2: Truyền nhận dữ liệu & Xử lý Form](02_Data_Transfer_va_Form.md)** *(Bài 3 & Bài 4)*
   * So sánh toàn diện `GET` vs `POST`.
   * Chuẩn hóa và bảo mật dữ liệu với `trim()`, `htmlspecialchars()`, `intval()`, `floatval()`.
   * Kỹ thuật Sticky Form giữ lại dữ liệu.
   * Upload file đơn và đa file với mảng `$_FILES` và hàm `move_uploaded_file()`.

3. **[Chuyên đề 3: Quản lý Phiên (Session) & Cookie](03_Session_va_Cookie.md)** *(Bài 5 & Bài 6)*
   * So sánh Session (Server-side) vs Cookie (Client-side).
   * Cơ chế xác thực và bảo vệ trang quản trị Admin Guard (`auth.php`).
   * 7 tham số của hàm `setcookie()`, cơ chế tự động hủy cookie.
   * Lưu trữ danh sách yêu thích JSON Cookie CRUD (`json_encode`, `json_decode`).

4. **[Chuyên đề 4: Hàm (Function) & Mảng (Array)](04_Ham_va_Mang.md)** *(Bài 7)*
   * Tổ chức thư viện hàm trong thư mục `libs/`.
   * Thao tác trên Mảng 1 chiều (`min`, `max`, `array_sum`, `sort`, `array_reverse`).
   * Thao tác trên Ma trận 2 chiều (Chéo chính, Chéo phụ, Tích ma trận, `array_map`).
   * Thao tác trên Mảng kết hợp (`array_key_exists`, `array_search`, `ksort`, `krsort`, `asort`, `arsort`).

5. **[Chuyên đề 5: Thao tác Đọc và Ghi File trong PHP](05_Doc_Ghi_File.md)** *(Bài 8)*
   * Bản chất lưu trữ File Text (`.txt`) và chuẩn định dạng 3 dòng/sinh viên.
   * Đọc file theo dòng bằng hàm `file()` với các cờ `FILE_IGNORE_NEW_LINES`, `FILE_SKIP_EMPTY_LINES`.
   * Ghi nối tiếp vào file bằng hàm `file_put_contents()` với cờ `FILE_APPEND`, `LOCK_EX`.
   * Bảng các chế độ mở file (`r`, `w`, `a`, `r+`, `w+`, `a+`).
