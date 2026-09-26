<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài tập sử dụng Template PHP</title>
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
                <img src="/src/image/pngtree-simply-pastel-minimal-and-relax-horizontal-line-between-peaceful-sea-and-image_15829105.jpg" alt="Banner">
            </div>
        </header>

        <!-- 2. Khung thân chính chia 2 cột -->
        <div class="container">
            <!-- Cột trái: Nhúng menu bài tập từ Bài 1 -->
            <?php
            if (file_exists('../Bai1/Menu.php')) {
                include '../Bai1/Menu.php';
            } else {
            ?>
                <aside class="sidebar">
                    <ul>
                        <li><a href="/Bai1/index.php">1. Tạo template</a></li>
                        <li><a href="/Bai2/Register.php">2. Sử dụng template</a></li>
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