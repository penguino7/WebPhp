<?php
// 1. Lấy tham số page từ URL (mặc định vào trang 'home')
$page = $_GET['page'] ?? 'home';

// 2. Nhúng Đầu trang & Menu bài tập bên trái (Dùng chung từ Bài 1)
include '../Bai1/Head.php';
include '../Bai1/Menu.php';
?>

<!-- 3. Cột nội dung chính bên phải của Bài 3 -->
<div class="main-content">
    <!-- Menu 5 tab chức năng của Bài 3 -->
    <div class="menu-nav">
        <a href="index.php?page=home" class="<?= ($page == 'home') ? 'active' : '' ?>">Home</a>
        <a href="index.php?page=drawTable" class="<?= ($page == 'drawTable') ? 'active' : '' ?>">DrawTable</a>
        <a href="index.php?page=calculate1" class="<?= ($page == 'calculate1') ? 'active' : '' ?>">Calculate1</a>
        <a href="index.php?page=calculate2" class="<?= ($page == 'calculate2') ? 'active' : '' ?>">Calculate2</a>
        <a href="index.php?page=array1" class="<?= ($page == 'array1') ? 'active' : '' ?>">Array1</a>
        <a href="index.php?page=array2" class="<?= ($page == 'array2' || $page == 'uploadprocess') ? 'active' : '' ?>">Array2</a>
    </div>

    <!-- Khung hiển thị nội dung động theo switch-case -->
    <div class="content">
        <?php
        switch ($page) {
            case 'drawTable':
                include 'pages/drawTables.php';
                break;

            case 'calculate1':
                include 'pages/calculate1.php';
                break;

            case 'calculate2':
                include 'pages/calculate2.php';
                break;

            case 'array1':
                include 'pages/array1.php';
                break;

            case 'array2':
                include 'pages/array2.php';
                break;

            case 'uploadprocess':
                include 'pages/uploadprocess.php';
                break;

            case 'home':
            default:
                include 'pages/home.php';
                break;
        }
        ?>
    </div>
</div>

<?php
// 4. Nhúng Chân trang (Dùng chung từ Bài 1)
include '../Bai1/Footer.php';
?>