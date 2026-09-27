<?php

/**
 * TRANG THÊM SINH VIÊN MỚI & GHI FILE (pages/addStudent.php)
 */

$ten = '';
$diachi = '';
$tuoi = '';
$error = '';
$success = '';

// 1. Xử lý khi người dùng gửi form (bấm nút "Ghi")
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $ten = trim($_POST['ten'] ?? '');
    $diachi = trim($_POST['diachi'] ?? '');
    $tuoi = trim($_POST['tuoi'] ?? '');

    // Kiểm tra tính hợp lệ của dữ liệu
    if ($ten === '' || $diachi === '' || $tuoi === '') {
        $error = 'Vui lòng điền đầy đủ tất cả các trường: Tên, Địa chỉ và Tuổi!';
    } elseif (!is_numeric($tuoi) || intval($tuoi) <= 0) {
        $error = 'Tuổi phải là một số nguyên dương hợp lệ!';
    } else {
        $filePath = __DIR__ . '/../student.txt';

        // Chuẩn bị dữ liệu 3 dòng liên tiếp kết thúc bằng ký tự xuống dòng
        $content = $ten . PHP_EOL . $diachi . PHP_EOL . intval($tuoi) . PHP_EOL;

        // Ghi nối tiếp (FILE_APPEND) vào cuối file student.txt
        $result = file_put_contents($filePath, $content, FILE_APPEND | LOCK_EX);

        if ($result !== false) {
            $success = 'Thêm sinh viên <strong>' . htmlspecialchars($ten) . '</strong> vào file thành công!';
            // Xóa trắng các trường nhập sau khi ghi thành công
            $ten = '';
            $diachi = '';
            $tuoi = '';
        } else {
            $error = 'Không thể ghi vào file student.txt. Vui lòng kiểm tra lại quyền ghi file!';
        }
    }
}
?>

<div class="student-container">
    <div class="form-header-title">THÊM SINH VIÊN MỚI</div>

    <p class="page-subtitle">
        Nhập thông tin sinh viên để lưu trữ nối tiếp vào cuối file <code>student.txt</code>.
    </p>

    <!-- Thông báo kết quả -->
    <?php if (!empty($success)): ?>
        <div class="alert-success">
            <?= $success ?>
            <div style="margin-top: 8px;">
                <a href="index.php?page=listStudent" style="color: #0f5132; font-weight: bold; text-decoration: underline;">
                    &rarr; Xem danh sách sinh viên ngay
                </a>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=addStudent">
        <div class="form-group">
            <label class="form-label" for="ten">Tên sinh viên:</label>
            <div class="form-control-wrap">
                <input
                    type="text"
                    id="ten"
                    name="ten"
                    value="<?= htmlspecialchars($ten) ?>"
                    placeholder="Ví dụ: Tran Quoc Tuan"
                    required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="diachi">Địa chỉ:</label>
            <div class="form-control-wrap">
                <input
                    type="text"
                    id="diachi"
                    name="diachi"
                    value="<?= htmlspecialchars($diachi) ?>"
                    placeholder="Ví dụ: Nam Dinh"
                    required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="tuoi">Tuổi:</label>
            <div class="form-control-wrap">
                <input
                    type="number"
                    id="tuoi"
                    name="tuoi"
                    value="<?= htmlspecialchars($tuoi) ?>"
                    placeholder="Ví dụ: 20"
                    min="1"
                    required>
            </div>
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn-submit">Ghi</button>
            <button type="button" class="btn-reset" onclick="window.location.href='index.php?page=addStudent'">Nhập lại</button>
            <button type="button" class="btn-reset" onclick="window.location.href='index.php?page=listStudent'">Xem danh sách</button>
        </div>
    </form>
</div>