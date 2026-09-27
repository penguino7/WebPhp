<?php
require_once __DIR__ . '/../libs/studentHelper.php';
$students = getAllStudents();
?>
<div class="student-container">
    <div class="page-title">
        <span>Danh Sách Sinh Viên</span>
        <a href="index.php?page=add" class="btn btn-primary btn-sm">+ Thêm sinh viên</a>
    </div>
    <div class="page-subtitle">Trang list.php: Liệt kê danh sách sinh viên từ file student.txt</div>

    <div class="table-responsive">
        <table class="student-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">STT</th>
                    <th>Ten</th>
                    <th>Ngay sinh</th>
                    <th>Dia chi</th>
                    <th class="text-center" style="width: 90px;">Anh</th>
                    <th class="text-center" style="width: 90px;">Lop</th>
                    <th class="text-center" style="width: 170px;">Thao tac</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 30px; color: #94a3b8;">
                            Hiện chưa có sinh viên nào trong danh sách. <a href="index.php?page=add">Thêm sinh viên ngay</a>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $id => $sv): ?>
                        <tr>
                            <td class="text-center" style="font-weight: 600; color: #64748b;"><?= $id + 1 ?></td>
                            <td style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($sv['name']) ?></td>
                            <td><?= htmlspecialchars($sv['birthday']) ?></td>
                            <td><?= htmlspecialchars($sv['address']) ?></td>
                            <td class="text-center">
                                <?php if (!empty($sv['image']) && file_exists(__DIR__ . '/../uploads/' . $sv['image'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($sv['image']) ?>" alt="Avatar" class="avatar-thumb">
                                <?php else: ?>
                                    <div class="avatar-placeholder">No img</div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge-class"><?= htmlspecialchars($sv['class']) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="action-links">
                                    <a href="index.php?page=detail&id=<?= $id ?>" class="action-link action-detail">Detail</a>
                                    <a href="index.php?page=edit&id=<?= $id ?>" class="action-link action-edit">Edit</a>
                                    <a href="index.php?page=delete&id=<?= $id ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên <?= htmlspecialchars(addslashes($sv['name'])) ?> không?');" class="action-link action-delete">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>