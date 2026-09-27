<h3 style="text-align: center; margin-top: 30px;">Đây là Home page của Bài 6: Cookie</h3>
<div class="cookie-intro-box" style="max-width: 580px; margin: 20px auto; padding: 20px 25px; background: #fafafa; border: 1px solid #e0e0e0; border-radius: 8px; line-height: 1.8; font-size: 14px; color: #333;">
    <p><b>Kiến thức về Cookies:</b></p>
    <ul>
        <li>Cookie là một đoạn dữ liệu được ghi trong máy Client do trình duyệt quản lý. Nó được trình duyệt gửi ngược lên server mỗi khi tải 1 trang web.</li>
        <li>Thường dùng để: Ghi nhớ đăng nhập (username, password), thời điểm login cuối (`lasttime`), danh sách ưa thích (`favourite list`)...</li>
        <li>Tạo cookie trong PHP: <code>setcookie("TenCookie", "GiaTri", time() + 30*24*3600);</code></li>
        <li>Đọc cookie trong PHP: <code>$_COOKIE["TenCookie"];</code></li>
    </ul>
    <p style="text-align: center; margin-top: 15px;">
        Vui lòng chuyển sang tab <b>Login</b> để kiểm tra tính năng tự động ghi nhớ tài khoản bằng Cookie.
    </p>
</div>