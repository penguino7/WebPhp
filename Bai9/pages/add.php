<?php
require_once __DIR__ . '/../libs/studentHelper.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullname'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $class    = trim($_POST['class'] ?? 'class1');
    $imageName = '1.jpg';

    if (empty($fullName) || empty($birthday) || empty($address) || empty($class)) {
        $error = 'Vui lòng nhập đầy đủ các trường thông tin!';
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
        } else {
            $error = 'Có lỗi xảy ra khi lưu vào file student.txt!';
        }
    }
}
?>
<div class="b9-content-wrap">
    <div class="b9-title">Trang add.php: add sinh viên mới, lưu vào file sinh viên hiện tại</div>

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
                    <td><input type="text" name="fullname" class="b9-input-text" required></td>
                </tr>
                <tr>
                    <td class="b9-form-label">Birthday:</td>
                    <td><input type="text" name="birthday" class="b9-input-text" placeholder="2012-08-12" required></td>
                </tr>
                <tr>
                    <td class="b9-form-label">Address:</td>
                    <td><input type="text" name="address" class="b9-input-text" required></td>
                </tr>
                <tr>
                    <td class="b9-form-label">Image:</td>
                    <td><input type="file" name="image" accept="image/*"></td>
                </tr>
                <tr>
                    <td class="b9-form-label">Class:</td>
                    <td>
                        <select name="class" class="b9-select">
                            <option value="class1">class1</option>
                            <option value="class2">class2</option>
                            <option value="class3">class3</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="padding-top: 10px;">
                        <button type="reset" class="b9-btn">Nhap lai</button>
                        <button type="submit" class="b9-btn b9-btn-submit">Luu</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>