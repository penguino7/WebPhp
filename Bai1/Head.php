<?php
// Tự động xác định đường dẫn tương đối về thư mục gốc (nơi chứa src/, Bai1, ...)
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
    <title>Bài Tập Thực Hành Công Nghệ Web An Toàn</title>
    <!-- 1. CSS khung giao diện chung của toàn website -->
    <link rel="stylesheet" href="<?= $relBase ?>src/css/common.css?v=<?php echo time(); ?>">
    <!-- 2. CSS riêng của bài đang mở -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="wrapper">
        <header>
            <div class="student-info">
                <p>Bùi Ngọc Nhất</p>
            </div>
            <div class="banner">
                <img src="<?= $relBase ?>src/image/pngtree-simply-pastel-minimal-and-relax-horizontal-line-between-peaceful-sea-and-image_15829105.jpg" alt="Banner Picture">
            </div>
        </header>

        <div class="container">