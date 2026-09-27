<?php
// 1. Lấy tham số page từ URL (mặc định vào trang 'home')
$page = $_GET['page'] ?? 'home';

// 2. Nhúng Đầu trang & Menu bài tập bên trái (Dùng chung từ Bài 1)
include_once __DIR__ . '/../Bai1/Head.php';
include_once __DIR__ . '/../Bai1/Menu.php';
?>

<!-- 3. Cột nội dung chính bên phải của Bài 9 -->
<div class="main-content">
    <!-- Menu các tab chức năng của Bài 9 -->
    <div class="menu-nav">
        <a href="index.php?page=home" class="<?= ($page == 'home') ? 'active' : '' ?>">Home</a>
        <a href="index.php?page=list" class="<?= (in_array($page, ['list', 'detail', 'edit'])) ? 'active' : '' ?>">List</a>
        <a href="index.php?page=add" class="<?= ($page == 'add') ? 'active' : '' ?>">Add</a>
    </div>

    <!-- Khung hiển thị nội dung động theo switch-case -->
    <div class="content">
        <?php
        switch ($page) {
            case 'list':
                include __DIR__ . '/pages/list.php';
                break;

            case 'add':
                include __DIR__ . '/pages/add.php';
                break;

            case 'detail':
                include __DIR__ . '/pages/detail.php';
                break;

            case 'edit':
                include __DIR__ . '/pages/edit.php';
                break;

            case 'delete':
                include __DIR__ . '/pages/delete.php';
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
