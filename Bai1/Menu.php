<?php
if (!isset($relBase)) {
    $relBase = '../';
    $currentScriptFile = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'] ?? ''));
    $rootProjectDir = str_replace('\\', '/', dirname(__DIR__));
    if (!empty($currentScriptFile) && strpos($currentScriptFile, $rootProjectDir) === 0) {
        $subPath = trim(substr($currentScriptFile, strlen($rootProjectDir)), '/');
        $depth = $subPath ? count(explode('/', $subPath)) : 0;
        $relBase = str_repeat('../', $depth);
    }
}
?>
<aside class="left-menu">
    <ul>
        <li><a href="<?= $relBase ?>Bai1/index.php">1. Tạo template</a></li>
        <li><a href="<?= $relBase ?>Bai2/Register.php">2. Sử dụng template</a></li>
        <li><a href="<?= $relBase ?>Bai3/index.php">3. Lấy dữ liệu và gửi dữ liệu</a></li>
        <li><a href="<?= $relBase ?>Bai4/index.php">4. GetForm</a></li>
        <li><a href="<?= $relBase ?>Bai5/index.php">5. Phiên</a></li>
        <li><a href="<?= $relBase ?>Bai6/index.php">6. Cookie</a></li>
        <li><a href="<?= $relBase ?>Bai7/index.php">7. Function</a></li>
        <li><a href="<?= $relBase ?>Bai8/index.php">8. Đọc, ghi file</a></li>
        <li><a href="<?= $relBase ?>Bai9/index.php">9. Thao tác file và data flow</a></li>
        <li><a href="<?= $relBase ?>Bai10/index.php">10. Website đa ngôn ngữ</a></li>
        <li><a href="<?= $relBase ?>Bai11/index.php">11. Kết nối và truy vấn CSDL cơ bản</a></li>
        <li><a href="<?= $relBase ?>Bai12/index.php">12. Truy vấn dữ liệu</a></li>
        <li><a href="<?= $relBase ?>Bai13/index.php">13. Web bán laptop (End user)</a></li>
        <li><a href="<?= $relBase ?>Bai14/index.php">14. Web bán laptop (Administration)</a></li>
        <li><a href="<?= $relBase ?>Bai15/index.php">15. Giỏ hàng Web bán laptop</a></li>
        <li><a href="<?= $relBase ?>Bai16/index.php">16. Tích hợp richtext box</a></li>
    </ul>
</aside>