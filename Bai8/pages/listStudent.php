<?php

/**
 * TRANG ĐỌC & HIỂN THỊ DANH SÁCH SINH VIÊN (pages/listStudent.php)
 */

// 1. Xác định đường dẫn file dữ liệu student.txt
$filePath = __DIR__ . '/../student.txt';
$students = [];

// 2. Đọc file nếu file tồn tại
if (file_exists($filePath)) {
    // Đọc tất cả các dòng vào mảng, tự động bỏ ký tự xuống dòng và dòng trống
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $totalLines = count($lines);

    // Cứ mỗi 3 dòng liên tiếp là 1 sinh viên (Tên -> Địa chỉ -> Tuổi)
    for ($i = 0; $i < $totalLines; $i += 3) {
        $students[] = [
            'ten'    => $lines[$i] ?? '',
            'diachi' => $lines[$i + 1] ?? '',
            'tuoi'   => $lines[$i + 2] ?? '',
        ];
    }
}
?>

<div class="student-container">
    <div class="form-header-title">DANH SÁCH SINH VIÊN</div>

    <p class="page-subtitle">
        Dữ liệu được đọc trực tiếp từ file văn bản <code>student.txt</code>.
    </p>

    <?php if (empty($students)): ?>
        <p style="text-align: center; color: #888; margin: 30px 0;">
            Danh sách sinh viên đang trống hoặc chưa có dữ liệu trong file <code>student.txt</code>!
        </p>
    <?php else: ?>
        <table class="student-table">
            <thead>
                <tr>
                    <th class="col-center" style="width: 60px;">STT</th>
                    <th>Tên</th>
                    <th>Địa chỉ</th>
                    <th class="col-center" style="width: 90px;">Tuổi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $index => $sv): ?>
                    <tr>
                        <td class="col-center"><?= $index + 1 ?></td>
                        <td><strong><?= htmlspecialchars($sv['ten']) ?></strong></td>
                        <td><?= htmlspecialchars($sv['diachi']) ?></td>
                        <td class="col-center"><?= htmlspecialchars($sv['tuoi']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="form-buttons">
        <button type="button" class="btn-submit" onclick="window.location.href='index.php?page=addStudent'">+ Thêm sinh viên mới</button>
    </div>
</div>