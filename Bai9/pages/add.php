<?php
require_once __DIR__ . '/../libs/studentHelper.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullname'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $class    = trim($_POST['class'] ?? '');
    $imageName = 'default.png';

    if (empty($fullName) || empty($birthday) || empty($address) || empty($class)) {
        $error = 'Vui lòng điền đầy đủ tất cả các trường thông tin!';
    } else {
        // Xử lý upload file ảnh nếu có
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
            $error = 'Có lỗi xảy ra khi lưu vào file!';
        }
    }
}
?>
<div style="padding: 20px; font-family: Arial, sans-serif;">
    <h3 style="margin-top: 0;">Trang add.php: add sinh viên mới, lưu vào file sinh viên hiện tại</h3>

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
                <td><input type="text" id="fullname" name="fullname" style="width: 100%;" required></td>
            </tr>
            <tr>
                <td style="padding: 6px 0;"><label for="birthday">Birthday:</label></td>
                <td><input type="date" id="birthday" name="birthday" style="width: 100%;" required></td>
            </tr>
            <tr>
                <td style="padding: 6px 0;"><label for="address">Address:</label></td>
                <td><input type="text" id="address" name="address" style="width: 100%;" required></td>
            </tr>
            <tr>
                <td style="padding: 6px 0;"><label for="image">Image:</label></td>
                <td><input type="file" id="image" name="image" accept="image/*"></td>
            </tr>
            <tr>
                <td style="padding: 6px 0;"><label for="class">Class:</label></td>
                <td>
                    <select id="class" name="class" style="width: 100%;">
                        <option value="class1">class1</option>
                        <option value="class2">class2</option>
                        <option value="class3">class3</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-top: 10px;">
                    <button type="reset">Nhập lại</button>
                    <button type="submit">Lưu</button>
                </td>
            </tr>
        </table>
    </form>
</div>
