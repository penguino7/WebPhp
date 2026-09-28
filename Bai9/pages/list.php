<?php
require_once __DIR__ . '/../libs/studentHelper.php';
$students = getAllStudents();
?>
<div class="b9-content-wrap">
    <div class="b9-title">Trang list.php: liệt kê danh sách sinh viên</div>

    <div class="b9-table-wrap">
        <table class="b9-table">
            <thead>
                <tr>
                    <th style="width: 45px;">STT</th>
                    <th>Ten</th>
                    <th style="width: 110px;">Ngay sinh</th>
                    <th>Dia chi</th>
                    <th style="width: 85px;">Anh</th>
                    <th style="width: 85px;">Lop</th>
                    <th style="width: 150px;">Thao tac</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 20px; color: #888;">
                            Chưa có dữ liệu sinh viên nào trong danh sách.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $id => $sv): ?>
                        <tr>
                            <td class="text-center"><?= $id + 1 ?></td>
                            <td class="text-left" style="font-weight: 500;"><?= htmlspecialchars($sv['name']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($sv['birthday']) ?></td>
                            <td class="text-left"><?= htmlspecialchars($sv['address']) ?></td>
                            <td class="text-center">
                                <?php if (!empty($sv['image']) && file_exists(__DIR__ . '/../uploads/' . $sv['image'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($sv['image']) ?>" alt="Avatar" class="b9-avatar-thumb">
                                <?php else: ?>
                                    <div class="b9-avatar-none">No img</div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= htmlspecialchars($sv['class']) ?></td>
                            <td class="text-center b9-action-links">
                                <a href="index.php?page=detail&id=<?= $id ?>">Detail</a> |
                                <a href="index.php?page=edit&id=<?= $id ?>">Edit</a> |
                                <a href="index.php?page=delete&id=<?= $id ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên <?= htmlspecialchars(addslashes($sv['name'])) ?>?');" class="action-delete">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>