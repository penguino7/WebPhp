<?php
// 1. Khởi tạo session để kiểm tra quyền truy cập Admin
session_start();

// 2. Lấy tham số page từ URL (mặc định là 'home')
$page = $_GET['page'] ?? 'home';

// 3. Nhúng Đầu trang & Menu bài tập bên trái (Dùng chung từ Bài 1)
include '../../Bai1/Head.php';
include '../../Bai1/Menu.php';

// 4. Kiểm tra xem người dùng đã đăng nhập chưa
$isLoggedIn = isset($_SESSION['Username']);
?>

<!-- 5. Cột nội dung chính của khu vực Admin Bài 6 -->
<div class="main-content">
    <?php if ($isLoggedIn): ?>
        <!-- Menu 4 tab chức năng của Admin Bài 6 -->
        <div class="menu-nav">
            <a href="../index.php">Return Home</a>
            <a href="index.php?page=home" class="<?= ($page == 'home') ? 'active' : '' ?>">Admin Home</a>
            <a href="index.php?page=favourite" class="<?= ($page == 'favourite') ? 'active' : '' ?>">Favourite List</a>
            <a href="index.php?page=logout" class="<?= ($page == 'logout') ? 'active' : '' ?>">Logout</a>
        </div>

        <!-- Khung hiển thị nội dung động theo switch-case -->
        <div class="content">
            <?php
            switch ($page) {
                case 'favourite':
                    include 'pages/favourite.php';
                    break;

                case 'logout':
                    include 'pages/logout.php';
                    break;

                case 'home':
                default:
                    include 'pages/home.php';
                    break;
            }
            ?>
        </div>
    <?php else: ?>
        <!-- Nếu CHƯA ĐĂNG NHẬP: Hiển thị cảnh báo và CHẶN truy cập -->
        <div class="content" style="text-align: center; padding: 40px 20px;">
            <h3 style="color: #dc3545; margin-bottom: 15px;">Chưa đăng nhập</h3>
            <p style="font-size: 15px; color: #555; margin-bottom: 25px;">
                Bạn chưa đăng nhập vào hệ thống! Không được phép sử dụng bất kỳ trang nào trong thư mục Quản trị (Admin).
            </p>
            <a href="../index.php?page=login" class="btn-submit" style="display: inline-block; text-decoration: none; padding: 9px 26px;">
                Đến trang Đăng nhập
            </a>
        </div>
    <?php endif; ?>
</div>

<?php
// 6. Nhúng Chân trang (Dùng chung từ Bài 1)
include '../../Bai1/Footer.php';
?>