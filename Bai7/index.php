<?php
// 1. Lấy tham số page từ URL (mặc định vào trang 'home')
$page = $_GET['page'] ?? 'home';

// 2. Nhúng Đầu trang & Menu bài tập bên trái (Dùng chung từ Bài 1)
include '../Bai1/Head.php';
include '../Bai1/Menu.php';
?>

<!-- 3. Cột nội dung chính bên phải của Bài 7 -->
<div class="main-content">
    <!-- Menu 4 tab chức năng của Bài 7 -->
    <div class="menu-nav">
        <a href="index.php?page=home" class="<?= ($page == 'home') ? 'active' : '' ?>">Home</a>
        <a href="index.php?page=ar1Chieu" class="<?= ($page == 'ar1Chieu') ? 'active' : '' ?>">Ar1Chieu</a>
        <a href="index.php?page=matrix" class="<?= ($page == 'matrix') ? 'active' : '' ?>">Matrix</a>
        <a href="index.php?page=associateArr" class="<?= ($page == 'associateArr') ? 'active' : '' ?>">AssociateArr</a>
    </div>

    <!-- Khung hiển thị nội dung động theo switch-case -->
    <div class="content">
        <?php
        switch ($page) {
            case 'ar1Chieu':
                include 'pages/ar1Chieu.php';
                break;

            case 'matrix':
                include 'pages/matrix.php';
                break;

            case 'associateArr':
                include 'pages/associateArr.php';
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