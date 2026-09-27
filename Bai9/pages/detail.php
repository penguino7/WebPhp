<?php
require_once __DIR__ . '/../libs/studentHelper.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : -1;
$student = getStudentByIndex($id);
?>
<div class="student-container">
    <div class="page-title">
        <span>Chi Tiết Hồ Sơ Sinh Viên</span>
        <div>
            <a href="index.php?page=list" class="btn btn-secondary btn-sm">Danh sách</a>
            <?php if ($student !== null): ?>
                <a href="index.php?page=edit&id=<?= $id ?>" class="btn btn-primary btn-sm">Sửa hồ sơ</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="page-subtitle">Trang detail.php: Hiển thị chi tiết hồ sơ cá nhân sinh viên</div>

    <?php if ($student === null): ?>
        <div class="alert-error">⚠ Không tìm thấy thông tin sinh viên yêu cầu trong cơ sở dữ liệu file!</div>
    <?php else: ?>
        <div class="profile-card">
            <div class="profile-avatar-wrap">
                <?php if (!empty($student['image']) && file_exists(__DIR__ . '/../uploads/' . $student['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($student['image']) ?>" alt="Avatar" class="profile-avatar">
                <?php else: ?>
                    <div class="profile-avatar" style="background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 14px;">Chưa có ảnh</div>
                <?php endif; ?>
            </div>

            <div class="profile-info">
                <div class="profile-name"><?= htmlspecialchars($student['name']) ?></div>

                <div class="profile-row">
                    <div class="profile-label">Ngày sinh:</div>
                    <div class="profile-value"><?= htmlspecialchars($student['birthday']) ?></div>
                </div>

                <div class="profile-row">
                    <div class="profile-label">Địa chỉ:</div>
                    <div class="profile-value"><?= htmlspecialchars($student['address']) ?></div>
                </div>

                <div class="profile-row">
                    <div class="profile-label">Lớp học:</div>
                    <div class="profile-value">
                        <span class="badge-class"><?= htmlspecialchars($student['class']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>