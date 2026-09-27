<?php
// 1. Lấy tham số page từ URL (mặc định vào trang 'home')
$page = $_GET['page'] ?? 'home';

// 2. Nhúng Đầu trang & Menu bài tập bên trái (Dùng chung từ Bài 1)
include_once __DIR__ . '/../Bai1/Head.php';
include_once __DIR__ . '/../Bai1/Menu.php';
?>

<!-- 3. Cột nội dung chính bên phải của Bài 8 -->
<div class="main-content">
    <!-- Menu 3 tab chức năng của Bài 8 -->
    <div class="menu-nav">
        <a href="index.php?page=home" class="<?= ($page == 'home') ? 'active' : '' ?>">Home</a>
        <a href="index.php?page=listStudent" class="<?= ($page == 'listStudent') ? 'active' : '' ?>">ListStudent</a>
        <a href="index.php?page=addStudent" class="<?= ($page == 'addStudent') ? 'active' : '' ?>">Add Student</a>
    </div>

    <!-- Khung hiển thị nội dung động theo switch-case -->
    <div class="content">
        <?php
        switch ($page) {
            case 'listStudent':
                include __DIR__ . '/pages/listStudent.php';
                break;

            case 'addStudent':
                include __DIR__ . '/pages/addStudent.php';
                break;

            case 'home':
            default:
                include __DIR__ . '/pages/home.php';
                break;
        }
        ?>
    </div>
</div>

<?php
// 4. Nhúng Chân trang (Dùng chung từ Bài 1)
include_once __DIR__ . '/../Bai1/Footer.php';
?>
