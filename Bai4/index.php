<?php
// 1. Lấy tham số page từ URL (mặc định vào trang 'home')
$page = $_GET['page'] ?? 'home';

// 2. Nhúng Đầu trang & Menu bài tập bên trái (Dùng chung từ Bài 1)
include '../Bai1/Head.php';
include '../Bai1/Menu.php';
?>

<!-- 3. Cột nội dung chính bên phải của Bài 4 -->
<div class="main-content">
    <!-- Menu 3 tab chức năng của Bài 4 -->
    <div class="menu-nav">
        <a href="index.php?page=home" class="<?= ($page == 'home') ? 'active' : '' ?>">Home</a>
        <a href="index.php?page=register" class="<?= ($page == 'register' || $page == 'registerProcess') ? 'active' : '' ?>">Register</a>
        <a href="index.php?page=contact1Page" class="<?= ($page == 'contact1Page') ? 'active' : '' ?>">Contact1Page</a>
    </div>

    <!-- Khung hiển thị nội dung động theo switch-case -->
    <div class="content">
        <?php
        switch ($page) {
            case 'register':
                include 'pages/register.php';
                break;

            case 'registerProcess':
                include 'pages/registerProcess.php';
                break;

            case 'contact1Page':
                include 'pages/contact1Page.php';
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
