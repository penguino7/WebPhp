<?php
require_once __DIR__ . '/../libs/studentHelper.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : -1;
$student = getStudentByIndex($id);
?>
<div class="b9-content-wrap">
    <div class="b9-title">Trang detail.php: Chi tiet sinh vien</div>

    <?php if ($student === null): ?>
        <div class="b9-alert-error">Không tìm thấy thông tin sinh viên!</div>
        <p><a href="index.php?page=list">← Quay lại danh sách</a></p>
    <?php else: ?>
        <div class="b9-detail-box">
            <div>
                <?php if (!empty($student['image']) && file_exists(__DIR__ . '/../uploads/' . $student['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($student['image']) ?>" alt="Avatar" class="b9-detail-avatar">
                <?php else: ?>
                    <div class="b9-detail-avatar" style="background:#eee; display:flex; align-items:center; justify-content:center; color:#888;">No Avatar</div>
                <?php endif; ?>
            </div>

            <div class="b9-detail-info">
                <div class="b9-detail-name"><?= htmlspecialchars($student['name']) ?></div>
                <div class="b9-detail-text"><?= htmlspecialchars($student['birthday']) ?></div>
                <div class="b9-detail-text"><?= htmlspecialchars($student['address']) ?></div>
                <div class="b9-detail-text"><?= htmlspecialchars($student['class']) ?></div>
            </div>
        </div>
        <p style="margin-top: 15px;"><a href="index.php?page=list">← Quay lại danh sách</a></p>
    <?php endif; ?>
</div>