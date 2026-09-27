<?php
require_once __DIR__ . '/../libs/studentHelper.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : -1;
$student = getStudentByIndex($id);

$message = '';
$error = '';

if ($student === null) {
    echo '<div class="student-container"><div class="alert-error">⚠ Không tìm thấy sinh viên để chỉnh sửa! <a href="index.php?page=list">Quay lại danh sách</a></div></div>';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullname'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $class    = trim($_POST['class'] ?? '');
    $imageName = $student['image']; // Mặc định giữ tên ảnh cũ

    if (empty($fullName) || empty($birthday) || empty($address) || empty($class)) {
        $error = 'Vui lòng điền đầy đủ tất cả các trường thông tin!';
    } else {
        // Nếu người dùng upload ảnh mới
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExt, $allowedExts, true)) {
                $targetDir = __DIR__ . '/../uploads/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $newFileName = time() . '_' . uniqid() . '.' . $fileExt;
                $targetPath  = $targetDir . $newFileName;

                if (move_uploaded_file($fileTmp, $targetPath)) {
                    // Xóa file ảnh cũ nếu có và không phải default
                    if (!empty($student['image']) && $student['image'] !== 'default.png') {
                        $oldPath = $targetDir . $student['image'];
                        if (file_exists($oldPath) && is_file($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                    $imageName = $newFileName;
                }
            }
        }

        $updatedData = [
            'name'     => $fullName,
            'birthday' => $birthday,
            'address'  => $address,
            'image'    => $imageName,
            'class'    => $class
        ];

        if (updateStudent($id, $updatedData)) {
            $message = 'Cập nhật thông tin sinh viên thành công!';
            $student = $updatedData; // Cập nhật lại dữ liệu hiển thị
        } else {
            $error = 'Không thể cập nhật dữ liệu vào file student.txt!';
        }
    }
}
?>
<div class="student-container">
    <div class="page-title">
        <span>Cập Nhật Thông Tin Sinh Viên</span>
        <a href="index.php?page=list" class="btn btn-secondary btn-sm">Danh sách</a>
    </div>
    <div class="page-subtitle">Trang Edit.php: Hiển thị thông tin cũ và cập nhật thông tin mới vào file student.txt</div>

    <?php if (!empty($message)): ?>
        <div class="alert-success">✓ <?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert-error">⚠ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="student-form">
        <div class="form-group">
            <label for="fullname" class="form-label">Full name: <span class="required">*</span></label>
            <div class="form-control-wrap">
                <input type="text" id="fullname" name="fullname" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="birthday" class="form-label">Birthday: <span class="required">*</span></label>
            <div class="form-control-wrap">
                <input type="date" id="birthday" name="birthday" class="form-control" value="<?= htmlspecialchars($student['birthday']) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Address: <span class="required">*</span></label>
            <div class="form-control-wrap">
                <input type="text" id="address" name="address" class="form-control" value="<?= htmlspecialchars($student['address']) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="image" class="form-label">Image:</label>
            <div class="form-control-wrap">
                <input type="file" id="image" name="image" class="form-control form-control-file" accept="image/*">
                <?php if (!empty($student['image']) && file_exists(__DIR__ . '/../uploads/' . $student['image'])): ?>
                    <div class="current-image-preview">
                        <img src="uploads/<?= htmlspecialchars($student['image']) ?>" alt="Current Avatar">
                        <span>Ảnh hiện tại: <strong><?= htmlspecialchars($student['image']) ?></strong> (để trống nếu không đổi)</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="class" class="form-label">Class: <span class="required">*</span></label>
            <div class="form-control-wrap">
                <select id="class" name="class" class="form-control">
                    <option value="class1" <?= ($student['class'] === 'class1') ? 'selected' : '' ?>>class1</option>
                    <option value="class2" <?= ($student['class'] === 'class2') ? 'selected' : '' ?>>class2</option>
                    <option value="class3" <?= ($student['class'] === 'class3') ? 'selected' : '' ?>>class3</option>
                </select>
            </div>
        </div>

        <div class="form-buttons">
            <a href="index.php?page=list" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>