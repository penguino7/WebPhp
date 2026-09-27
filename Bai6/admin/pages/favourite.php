<h3 style="text-align: center; margin-bottom: 20px;">Danh sách Web Links Ưa Thích (Favourite List)</h3>

<?php
// 1. ĐỌC COOKIE: Lấy chuỗi JSON từ Cookie và chuyển thành mảng PHP
$rawCookie = $_COOKIE['favourite_links'] ?? '[]';
$favouriteList = json_decode($rawCookie, true);
if (!is_array($favouriteList)) {
    $favouriteList = [];
}

$msg = '';

// 2. XỬ LÝ KHI THÊM LINK MỚI
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btnAddLink'])) {
    $siteName = trim($_POST['site_name'] ?? '');
    $siteUrl  = trim($_POST['site_url'] ?? '');

    if (!empty($siteName) && !empty($siteUrl)) {
        // Tự động thêm https:// nếu người dùng quên nhập giao thức
        if (!preg_match("~^(?:f|ht)tps?://~i", $siteUrl)) {
            $siteUrl = "https://" . $siteUrl;
        }

        // Thêm link mới vào mảng
        $favouriteList[] = [
            'title' => $siteName,
            'url'   => $siteUrl
        ];

        // LƯU COOKIE: Chuyển mảng thành chuỗi JSON và lưu vào Cookie 30 ngày
        setcookie('favourite_links', json_encode($favouriteList), time() + 30 * 24 * 3600, '/');

        // Tải lại trang để cập nhật ngay danh sách hiển thị
        echo "<script>window.location.href='index.php?page=favourite';</script>";
        exit();
    } else {
        $msg = "Vui lòng nhập đầy đủ Tên website và Địa chỉ URL!";
    }
}

// 3. XỬ LÝ KHI XÓA TẤT CẢ LINK (HỦY COOKIE)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btnClearAll'])) {
    // Đặt thời gian hết hạn về quá khứ để trình duyệt xóa Cookie
    setcookie('favourite_links', '', time() - 3600, '/');
    echo "<script>window.location.href='index.php?page=favourite';</script>";
    exit();
}
?>

<div class="session-form-container" style="max-width: 560px;">
    <div class="form-header-title">Thêm Web Link mới vào Cookie</div>

    <!-- Thông báo lỗi nếu có -->
    <?php if (!empty($msg)): ?>
        <div class="error-message" style="margin-bottom: 15px;">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label class="form-label" style="width: 130px; min-width: 130px;">Tên website:</label>
            <div class="form-control-wrap">
                <input type="text" name="site_name" placeholder="Ví dụ: Google, Youtube, Github..." required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" style="width: 130px; min-width: 130px;">Địa chỉ URL:</label>
            <div class="form-control-wrap">
                <input type="text" name="site_url" placeholder="Ví dụ: google.com hoặc https://..." required>
            </div>
        </div>

        <div class="form-buttons">
            <button type="submit" name="btnAddLink" class="btn-submit">
                + Thêm vào Cookie
            </button>
            <?php if (!empty($favouriteList)): ?>
                <button type="submit" name="btnClearAll" class="btn-reset" onclick="return confirm('Bạn có chắc chắn muốn xóa toàn bộ danh sách link trong Cookie?');">
                    Xóa tất cả Cookie
                </button>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Khối hiển thị danh sách link đọc được từ Cookie -->
<div class="result-display-container" style="max-width: 560px;">
    <div class="form-header-title" style="color: #28a745;">
        Danh sách Web Link đang lưu trong Cookie (<?= count($favouriteList) ?>)
    </div>

    <?php if (!empty($favouriteList)): ?>
        <ul class="favourite-list">
            <?php foreach ($favouriteList as $index => $item): ?>
                <li>
                    <div>
                        <b><?= ($index + 1) ?>. <?= htmlspecialchars($item['title']) ?></b><br>
                        <a href="<?= htmlspecialchars($item['url']) ?>" target="_blank">
                            <?= htmlspecialchars($item['url']) ?> ↗
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="text-align: center; color: #777; font-style: italic; padding: 15px 0;">
            Chưa có liên kết nào được lưu trong Cookie. Hãy thêm liên kết đầu tiên ở form trên!
        </p>
    <?php endif; ?>
</div>