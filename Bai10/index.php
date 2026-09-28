<?php
// 1. Khởi động Session để quản lý lựa chọn ngôn ngữ
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Lấy tham số page từ URL Query String (mặc định 'home')
$page = $_GET['page'] ?? 'home';

// 3. Xử lý khi người dùng bấm nút chuyển đổi ngôn ngữ
if (isset($_POST['btnEnglish']) || (isset($_GET['lang']) && $_GET['lang'] === 'english')) {
    $_SESSION['lang'] = 'english';
    header("Location: index.php?page={$page}");
    exit;
}

if (isset($_POST['btnVietnamese']) || (isset($_GET['lang']) && $_GET['lang'] === 'vietnamese')) {
    $_SESSION['lang'] = 'vietnamese';
    header("Location: index.php?page={$page}");
    exit;
}

// 4. Xác định ngôn ngữ hiện tại (mặc định là 'english' như giáo trình)
$lang = $_SESSION['lang'] ?? 'english';

// 5. Nạp gói ngôn ngữ tương ứng
$langFile = __DIR__ . "/lang/{$lang}.php";
if (file_exists($langFile)) {
    require_once $langFile;
} else {
    require_once __DIR__ . '/lang/english.php';
}

// 6. Nhúng Đầu trang & Menu bài tập bên trái (Dùng chung từ Bài 1)
include_once __DIR__ . '/../Bai1/Head.php';
include_once __DIR__ . '/../Bai1/Menu.php';
?>

<!-- 7. Cột nội dung chính bên phải của Bài 10 -->
<div class="main-content">
    <!-- Thanh điều hướng đa ngôn ngữ: Bên trái là nút chọn ngôn ngữ, bên phải là menu trang -->
    <div class="b10-nav-container">
        <!-- Khối chọn ngôn ngữ -->
        <div class="b10-lang-switcher">
            <form method="POST" action="index.php?page=<?= htmlspecialchars($page) ?>" style="margin: 0; display: inline-flex; gap: 4px;">
                <input type="hidden" name="page" value="<?= htmlspecialchars($page) ?>">
                <input type="submit" name="btnVietnamese" value="Vietnamese" class="b10-lang-btn <?= ($lang === 'vietnamese') ? 'active' : '' ?>">
                <input type="submit" name="btnEnglish" value="English" class="b10-lang-btn <?= ($lang === 'english') ? 'active' : '' ?>">
            </form>
        </div>

        <!-- Khối Menu các trang con sử dụng HẰNG SỐ ĐA NGÔN NGỮ -->
        <div class="b10-menu-links">
            <a href="index.php?page=home" class="<?= ($page === 'home') ? 'active' : '' ?>"><?= HOME ?></a>
            <a href="index.php?page=contact" class="<?= ($page === 'contact') ? 'active' : '' ?>"><?= CONTACT ?></a>
            <a href="index.php?page=introduction" class="<?= ($page === 'introduction') ? 'active' : '' ?>"><?= INTRODUCTION ?></a>
            <a href="index.php?page=login" class="<?= ($page === 'login') ? 'active' : '' ?>"><?= LOGIN ?></a>
        </div>
    </div>

    <!-- Khung hiển thị nội dung động theo switch-case -->
    <div class="content">
        <?php
        switch ($page) {
            case 'contact':
                include __DIR__ . '/pages/contact.php';
                break;

            case 'introduction':
                include __DIR__ . '/pages/introduction.php';
                break;

            case 'login':
                include __DIR__ . '/pages/login.php';
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
// 8. Nhúng Chân trang (Dùng chung từ Bài 1)
include_once __DIR__ . '/../Bai1/Footer.php';
?>
