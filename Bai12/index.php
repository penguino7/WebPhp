<?php
// ==========================================================================
// BÀI 12: BỘ ĐIỀU HƯỚNG CHÍNH (SINGLE ENTRY POINT ROUTER)
// ==========================================================================

// 1. Lấy tham số trang từ URL (mặc định vào 'home')
$page = $_GET['page'] ?? 'home';

// 2. Nhúng Đầu trang & Menu bài tập bên trái (Dùng chung từ Bài 1)
include_once __DIR__ . '/../Bai1/Head.php';
include_once __DIR__ . '/../Bai1/Menu.php';
?>

<!-- 3. Cột nội dung chính bên phải của Bài 12 -->
<div class="main-content">
    <!-- Thanh menu tab ngang chuẩn hệ thống -->
    <div class="menu-nav">
        <a href="index.php?page=home" class="<?= ($page === 'home') ? 'active' : '' ?>">
            Danh Sách Lớp Học
        </a>
        <a href="index.php?page=listStudentsInClass" class="<?= in_array($page, ['listStudentsInClass', 'studentDetail']) ? 'active' : '' ?>">
            Sinh Viên Trong Lớp
        </a>
    </div>

    <!-- Khung hiển thị nội dung động theo switch-case -->
    <div class="content">
        <?php
        switch ($page) {
            case 'listStudentsInClass':
                include __DIR__ . '/pages/listStudentsInClass.php';
                break;

            case 'studentDetail':
                include __DIR__ . '/pages/studentDetail.php';
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