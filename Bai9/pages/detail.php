<?php
require_once __DIR__ . '/../libs/studentHelper.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : -1;
$student = getStudentByIndex($id);
?>
<div style="padding: 20px; font-family: Arial, sans-serif;">
    <h3 style="margin-top: 0;">Trang detail.php: Chi tiết sinh viên</h3>

    <?php if ($student === null): ?>
        <p style="color: red;">Không tìm thấy sinh viên yêu cầu!</p>
        <p><a href="index.php?page=list">Quay lại danh sách</a></p>
    <?php else: ?>
        <div style="display: flex; gap: 30px; align-items: center; max-width: 500px; padding: 20px; background: #fff; border: 1px solid #ddd;">
            <div>
                <?php if (!empty($student['image']) && file_exists(__DIR__ . '/../uploads/' . $student['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($student['image']) ?>" alt="Avatar" width="140" height="140" style="object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                <?php else: ?>
                    <div style="width: 140px; height: 140px; background: #eee; display: flex; align-items: center; justify-content: center; color: #888;">No Avatar</div>
                <?php endif; ?>
            </div>
            <div style="line-height: 2;">
                <div style="font-size: 18px; font-weight: bold; color: #333;"><?= htmlspecialchars($student['name']) ?></div>
                <div style="color: #666;"><?= htmlspecialchars($student['birthday']) ?></div>
                <div style="color: #666;"><?= htmlspecialchars($student['address']) ?></div>
                <div style="color: #0d6efd; font-weight: bold;"><?= htmlspecialchars($student['class']) ?></div>
            </div>
        </div>
        <p style="margin-top: 15px;"><a href="index.php?page=list">← Quay lại danh sách</a></p>
    <?php endif; ?>
</div>