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
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 1. CSS khung giao diện chung toàn website -->
    <link rel="stylesheet" href="<?= $relBase ?>src/css/common.css?v=<?php echo time(); ?>">
    <!-- 2. CSS riêng của Bài 2 -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="wrapper">
        <!-- 1. Header (Thông tin sinh viên & Banner) -->
        <header>
            <div class="student-info">
                <p>Bùi Ngọc Nhất</p>
            </div>
            <div class="banner">
                <img src="<?= $relBase ?>src/image/pngtree-simply-pastel-minimal-and-relax-horizontal-line-between-peaceful-sea-and-image_15829105.jpg" alt="Banner">
            </div>
        </header>

        <!-- 2. Khung thân chính chia 2 cột -->
        <div class="container">
            <!-- Cột trái: Nhúng menu bài tập từ Bài 1 -->
            <?php
            if (file_exists(__DIR__ . '/../Bai1/Menu.php')) {
                include __DIR__ . '/../Bai1/Menu.php';
            } else {
            ?>
                <aside class="sidebar">
                    <ul>
                        <li><a href="<?= $relBase ?>Bai1/index.php">1. Tạo template</a></li>
                        <li><a href="<?= $relBase ?>Bai2/Register.php">2. Sử dụng template</a></li>
                    </ul>
                </aside>
            <?php } ?>

            <!-- Cột phải: Menu chức năng và Nội dung trang -->
            <div class="main-content">
                <div class="menu-nav">
                    <a href="Register.php">Register</a>
                    <a href="RegisterResult.php">ResultRegister</a>
                    <a href="Caculate.php">Calculate</a>
                </div>

                <div class="content">