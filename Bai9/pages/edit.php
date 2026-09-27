<?php
require_once __DIR__ . '/../libs/studentHelper.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : -1;
$student = getStudentByIndex($id);

$message = '';
$error = '';

if ($student === null) {
    echo '<div style="padding: 20px; color: red;">Không tìm thấy sinh viên để chỉnh sửa! <a href="index.php?page=list">Quay lại</a></div>';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullname'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $class    = trim($_POST['class'] ?? '');
    $imageName = $student['image']; // Giữ ảnh cũ mặc định

    if (empty($fullName) || empty($birthday) || empty($address) || empty($class)) {
        $error = 'Vui lòng điền đầy đủ tất cả các trường!';
    } else {
        // Nếu người dùng upload ảnh mới
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExt, $allowedExts, true)) {
                $targetDir = __DIR__ . '/../uploads/';
                $newFileName = time() . '_' . uniqid() . '.' . $fileExt;
                $targetPath  = $targetDir . $newFileName;

                if (move_uploaded_file($fileTmp, $targetPath)) {
                    // Xóa ảnh cũ nếu có
                    if (!empty($student['image']) && $student['image'] !== 'default.png') {
                        @unlink($targetDir . $student['image']);
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
            $error = 'Không thể cập nhật dữ liệu vào file!';
        }
    }
}
?>
<div style="padding: 20px; font-family: Arial, sans-serif;">
    <h3 style="margin-top: 0;">Trang Edit.php: cho phép hiển thị thông tin cũ và cập nhật thông tin mới</h3>

    <?php if (!empty($message)): ?>
        <p style="color: green; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="max-width: 450px; background: #fafafa; padding: 15px; border: 1px solid #ddd;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 100px; padding: 6px 0;"><label for="fullname">Full name:</label></td>
                <td><input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($student['name']) ?>" style="width: 100%;" required></td>
            </tr>
            <tr>
                <td style="padding: 6px 0;"><label for="birthday">Birthday:</label></td>
                <td><input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($student['birthday']) ?>" style="width: 100%;" required></td>
            </tr>
            <tr>
                <td style="padding: 6px 0;"><label for="address">Address:</label></td>
                <td><input type="text" id="address" name="address" value="<?= htmlspecialchars($student['address']) ?>" style="width: 100%;" required></td>
            </tr>
            <tr>
                <td style="padding: 6px 0;"><label for="image">Image:</label></td>
                <td>
                    <input type="file" id="image" name="image" accept="image/*">
                    <?php if (!empty($student['image'])): ?>
                        <div style="font-size: 12px; color: #666; margin-top: 4px;">Ảnh hiện tại: <?= htmlspecialchars($student['image']) ?></div>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="padding: 6px 0;"><label for="class">Class:</label></td>
                <td>
                    <select id="class" name="class" style="width: 100%;">
                        <option value="class1" <?= ($student['class'] === 'class1') ? 'selected' : '' ?>>class1</option>
                        <option value="class2" <?= ($student['class'] === 'class2') ? 'selected' : '' ?>>class2</option>
                        <option value="class3" <?= ($student['class'] === 'class3') ? 'selected' : '' ?>>class3</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-top: 10px;">
                    <button type="reset">Nhập lại</button>
                    <button type="submit">Lưu</button>
                    <a href="index.php?page=list" style="margin-left: 10px;">Hủy</a>
                </td>
            </tr>
        </table>
    </form>
</div>
