<?php
// ==========================================================================
// BÀI 11: BỘ ĐIỀU HƯỚNG CHÍNH (SINGLE ENTRY POINT ROUTER)
// ==========================================================================

// 1. Lấy tham số trang từ URL (mặc định vào 'lop_list')
$page = $_GET['page'] ?? 'lop_list';

// 2. Nhúng Đầu trang & Menu bài tập bên trái (Dùng chung từ Bài 1)
include_once __DIR__ . '/../Bai1/Head.php';
include_once __DIR__ . '/../Bai1/Menu.php';
?>

<!-- 3. Cột nội dung chính bên phải của Bài 11 -->
<div class="main-content">
    <!-- Menu điều hướng các chức năng Bài 11 -->
    <div class="menu-nav">
        <a href="index.php?page=lop_list" class="<?= in_array($page, ['lop_list', 'lop_form']) ? 'active' : '' ?>">
            Quản Lý Lớp Học
        </a>
        <a href="index.php?page=hoso_list" class="<?= in_array($page, ['hoso_list', 'hoso_form']) ? 'active' : '' ?>">
            Quản Lý Hồ Sơ Học Sinh
        </a>
    </div>

    <!-- Khung hiển thị nội dung động theo switch-case -->
    <div class="content">
        <?php
        switch ($page) {
            case 'lop_form':
                include __DIR__ . '/pages/lop_form.php';
                break;

            case 'hoso_list':
                include __DIR__ . '/pages/hoso_list.php';
                break;

            case 'hoso_form':
                include __DIR__ . '/pages/hoso_form.php';
                break;

            case 'hoso_delete':
                include __DIR__ . '/pages/hoso_delete.php';
                break;

            case 'lop_list':
            default:
                include __DIR__ . '/pages/lop_list.php';
                break;
        }
        ?>
    </div>
</div>

<?php
// 4. Nhúng Chân trang (Dùng chung từ Bài 1)
include_once __DIR__ . '/../Bai1/Footer.php';
?>