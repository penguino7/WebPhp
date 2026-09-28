<?php
require_once __DIR__ . '/../libs/studentHelper.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : -1;
$student = getStudentByIndex($id);

$message = '';
$error = '';

if ($student === null) {
    echo '<div class="b9-content-wrap"><div class="b9-alert-error">Không tìm thấy sinh viên để chỉnh sửa! <a href="index.php?page=list">Quay lại</a></div></div>';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullname'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $class    = trim($_POST['class'] ?? '');
    $imageName = $student['image']; // Mặc định giữ tên ảnh cũ

    if (empty($fullName) || empty($birthday) || empty($address) || empty($class)) {
        $error = 'Vui lòng nhập đầy đủ các trường thông tin!';
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
            $student = $updatedData;
        } else {
            $error = 'Không thể lưu vào file student.txt!';
        }
    }
}
?>
<div class="b9-content-wrap">
    <div class="b9-title">Trang Edit.php: cho phép hiển thị thông tin cũ và cập nhật thông tin mới</div>

    <?php if (!empty($message)): ?>
        <div class="b9-alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="b9-alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="b9-form-box">
        <div style="font-weight: bold; margin-bottom: 12px; font-size: 14px;">Them sinh vien moi</div>
        <form method="POST" enctype="multipart/form-data">
            <table class="b9-form-table">
                <tr>
                    <td class="b9-form-label">Full name:</td>
                    <td><input type="text" name="fullname" class="b9-input-text" value="<?= htmlspecialchars($student['name']) ?>" required></td>
                </tr>
                <tr>
                    <td class="b9-form-label">Birthday:</td>
                    <td><input type="text" name="birthday" class="b9-input-text" value="<?= htmlspecialchars($student['birthday']) ?>" required></td>
                </tr>
                <tr>
                    <td class="b9-form-label">Address:</td>
                    <td><input type="text" name="address" class="b9-input-text" value="<?= htmlspecialchars($student['address']) ?>" required></td>
                </tr>
                <tr>
                    <td class="b9-form-label">Image:</td>
                    <td>
                        <input type="file" name="image" accept="image/*">
                        <?php if (!empty($student['image'])): ?>
                            <div style="font-size: 12px; color: #666; margin-top: 3px;">Ảnh hiện tại: <?= htmlspecialchars($student['image']) ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="b9-form-label">Class:</td>
                    <td>
                        <select name="class" class="b9-select">
                            <option value="class1" <?= ($student['class'] === 'class1') ? 'selected' : '' ?>>class1</option>
                            <option value="class2" <?= ($student['class'] === 'class2') ? 'selected' : '' ?>>class2</option>
                            <option value="class3" <?= ($student['class'] === 'class3') ? 'selected' : '' ?>>class3</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="padding-top: 10px;">
                        <button type="reset" class="b9-btn">Nhap lai</button>
                        <button type="submit" class="b9-btn b9-btn-submit">Luu</button>
                        <a href="index.php?page=list" style="margin-left: 10px; font-size: 13px; color: #666;">Quay lại</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>