<?php
// Khởi tạo session
session_start();

// 1. Lấy tham số page từ URL (mặc định là 'home')
$page = $_GET['page'] ?? 'home';

// 2. Nhúng Đầu trang & Menu bài tập bên trái (Dùng chung từ Bài 1)
include '../Bai1/Head.php';
include '../Bai1/Menu.php';
?>

<!-- 3. Cột nội dung chính của Bài 6 (End User) -->
<div class="main-content">
    <!-- Menu 2 tab chức năng của End User -->
    <div class="menu-nav">
        <a href="index.php?page=home" class="<?= ($page == 'home') ? 'active' : '' ?>">Home</a>
        <a href="index.php?page=login" class="<?= ($page == 'login') ? 'active' : '' ?>">Login</a>
    </div>

    <!-- Khung hiển thị nội dung động theo switch-case -->
    <div class="content">
        <?php
        switch ($page) {
            case 'login':
                include 'pages/login.php';
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