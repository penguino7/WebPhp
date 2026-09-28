<?php

/**
 * Footer giao diện Website Bán Laptop (Bài 13)
 */
?>
<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="footer-grid">
                <!-- Col 1: Giới thiệu -->
                <div class="footer-col">
                    <div class="footer-logo">
                        💻 LaptopShop<span>.vn</span>
                    </div>
                    <p class="footer-desc">
                        Hệ thống bán lẻ laptop chính hãng hàng đầu Việt Nam. Cam kết 100% hàng mới, bảo hành chính hãng, miễn phí giao hàng toàn quốc.
                    </p>
                    <div class="cert-badges">
                        <span class="badge-cert">✓ 100% Chính Hãng</span>
                        <span class="badge-cert">✓ Đổi mới 30 ngày</span>
                    </div>
                </div>

                <!-- Col 2: Hướng dẫn & Chính sách -->
                <div class="footer-col">
                    <h4 class="footer-heading">Chính sách & Hỗ trợ</h4>
                    <ul class="footer-links">
                        <li><a href="javascript:alert('Chính sách bảo hành 12-24 tháng');">Chính sách bảo hành</a></li>
                        <li><a href="javascript:alert('Chính sách đổi trả trong 30 ngày');">Chính sách đổi trả 1-1</a></li>
                        <li><a href="javascript:alert('Chính sách giao hàng hỏa tốc 2H');">Giao hàng hỏa tốc</a></li>
                        <li><a href="javascript:alert('Chính sách bảo mật thông tin');">Chính sách bảo mật</a></li>
                        <li><a href="javascript:alert('Hướng dẫn trả góp 0% lãi suất');">Trả góp 0% lãi suất</a></li>
                    </ul>
                </div>

                <!-- Col 3: Hãng laptop nổi bật -->
                <div class="footer-col">
                    <h4 class="footer-heading">Hãng Laptop Nổi Bật</h4>
                    <ul class="footer-links">
                        <li><a href="index.php?page=productList&cat_id=1">Laptop DELL Inspiron / XPS / Gaming</a></li>
                        <li><a href="index.php?page=productList&cat_id=2">Laptop HP Pavilion / Envy / Omen</a></li>
                        <li><a href="index.php?page=productList&cat_id=8">Apple MacBook Air & MacBook Pro</a></li>
                        <li><a href="index.php?page=productList&cat_id=6">Laptop ASUS Zenbook / ROG / TUF</a></li>
                        <li><a href="index.php?page=productList&cat_id=4">Laptop LENOVO ThinkPad / Legion</a></li>
                    </ul>
                </div>

                <!-- Col 4: Liên hệ & Hệ thống Showroom -->
                <div class="footer-col">
                    <h4 class="footer-heading">Tổng đài & Showroom</h4>
                    <p><strong>Hotline Bán Hàng:</strong> 1800 6868</p>
                    <p><strong>Hotline Kỹ Thuật:</strong> 1800 6869</p>
                    <p><strong>Showroom Hà Nội:</strong> 123 Phố Thái Hà, Đống Đa</p>
                    <p><strong>Showroom TP.HCM:</strong> 456 Đường Cách Mạng Tháng 8, Q.10</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-content">
            <p>&copy; <?= date('Y') ?> LaptopShop.vn - Phân hệ End User (Bài tập 13 PHP & MySQL). All rights reserved.</p>
            <p><a href="../Bai1/index.php" style="color:#aaa;">Quay lại Hệ thống Bài tập PHP</a></p>
        </div>
    </div>
</footer>