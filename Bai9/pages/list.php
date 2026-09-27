<?php
require_once __DIR__ . '/../libs/studentHelper.php';
$students = getAllStudents();
?>
<div style="padding: 20px; font-family: Arial, sans-serif;">
    <h3 style="margin-top: 0;">Trang list.php: liệt kê danh sách sinh viên</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; text-align: center;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>STT</th>
                <th>Ten</th>
                <th>Ngay sinh</th>
                <th>Dia chi</th>
                <th>Anh</th>
                <th>Lop</th>
                <th>Thao tac</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="7" style="color: #888;">Chưa có dữ liệu sinh viên.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $id => $sv): ?>
                    <tr>
                        <td><?= $id + 1 ?></td>
                        <td style="text-align: left;"><?= htmlspecialchars($sv['name']) ?></td>
                        <td><?= htmlspecialchars($sv['birthday']) ?></td>
                        <td style="text-align: left;"><?= htmlspecialchars($sv['address']) ?></td>
                        <td>
                            <?php if (!empty($sv['image']) && file_exists(__DIR__ . '/../uploads/' . $sv['image'])): ?>
                                <img src="uploads/<?= htmlspecialchars($sv['image']) ?>" alt="Avatar" width="50" height="50" style="border-radius: 4px; object-fit: cover;">
                            <?php else: ?>
                                <span style="color: #999;">No image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($sv['class']) ?></td>
                        <td>
                            <a href="index.php?page=detail&id=<?= $id ?>">Detail</a> |
                            <a href="index.php?page=edit&id=<?= $id ?>">Edit</a> |
                            <a href="index.php?page=delete&id=<?= $id ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');" style="color: red;">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>