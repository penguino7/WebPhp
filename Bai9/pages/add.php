<?php
require_once __DIR__ . '/../libs/studentHelper.php';

$message = '';
$error = '';
$fullName = '';
$birthday = '';
$address  = '';
$class    = 'class1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullname'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $class    = trim($_POST['class'] ?? 'class1');
    $imageName = 'default.png';

    if (empty($fullName) || empty($birthday) || empty($address) || empty($class)) {
        $error = 'Vui lòng điền đầy đủ tất cả các trường thông tin!';
    } else {
        // Xử lý upload ảnh nếu có
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
                    $imageName = $newFileName;
                }
            }
        }

        $newStudent = [
            'name'     => $fullName,
            'birthday' => $birthday,
            'address'  => $address,
            'image'    => $imageName,
            'class'    => $class
        ];

        if (addStudent($newStudent)) {
            $message = 'Thêm sinh viên mới thành công!';
            // Reset form sau khi thêm thành công
            $fullName = '';
            $birthday = '';
            $address  = '';
            $class    = 'class1';
        } else {
            $error = 'Có lỗi xảy ra khi lưu vào file student.txt!';
        }
    }
}
?>
<div class="student-container">
    <div class="page-title">
        <span>Thêm Sinh Viên Mới</span>
        <a href="index.php?page=list" class="btn btn-secondary btn-sm">Danh sách</a>
    </div>
    <div class="page-subtitle">Trang add.php: Thêm sinh viên mới, lưu nối tiếp vào file student.txt</div>

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
                <input type="text" id="fullname" name="fullname" class="form-control" value="<?= htmlspecialchars($fullName) ?>" placeholder="Nhập họ và tên..." required>
            </div>
        </div>

        <div class="form-group">
            <label for="birthday" class="form-label">Birthday: <span class="required">*</span></label>
            <div class="form-control-wrap">
                <input type="date" id="birthday" name="birthday" class="form-control" value="<?= htmlspecialchars($birthday) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="address" class="form-label">Address: <span class="required">*</span></label>
            <div class="form-control-wrap">
                <input type="text" id="address" name="address" class="form-control" value="<?= htmlspecialchars($address) ?>" placeholder="Nhập địa chỉ..." required>
            </div>
        </div>

        <div class="form-group">
            <label for="image" class="form-label">Image:</label>
            <div class="form-control-wrap">
                <input type="file" id="image" name="image" class="form-control form-control-file" accept="image/*">
            </div>
        </div>

        <div class="form-group">
            <label for="class" class="form-label">Class: <span class="required">*</span></label>
            <div class="form-control-wrap">
                <select id="class" name="class" class="form-control">
                    <option value="class1" <?= ($class === 'class1') ? 'selected' : '' ?>>class1</option>
                    <option value="class2" <?= ($class === 'class2') ? 'selected' : '' ?>>class2</option>
                    <option value="class3" <?= ($class === 'class3') ? 'selected' : '' ?>>class3</option>
                </select>
            </div>
        </div>

        <div class="form-buttons">
            <button type="reset" class="btn btn-secondary">Nhập lại</button>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>
</div>