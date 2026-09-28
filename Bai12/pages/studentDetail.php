<?php
// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../libs/connectDB.php';

// 2. Lấy mã sinh viên từ GET
$studentID = isset($_GET['id']) ? mysqli_real_escape_string($conn, trim($_GET['id'])) : 'SV001';

// 3. Truy vấn chi tiết sinh viên kèm tên lớp (JOIN với bảng classes)
$sql = "SELECT S.*, C.ClassName 
        FROM students S 
        LEFT JOIN classes C ON S.ClassID = C.ID 
        WHERE S.ID = '{$studentID}'";
$result = mysqli_query($conn, $sql);
$student = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;
?>

<!-- GIAO DIỆN: CHI TIẾT SINH VIÊN (pages/studentDetail.php) -->
<div class="page-header">
    <h2 class="page-title">THÔNG TIN CHI TIẾT SINH VIÊN</h2>
    <div class="action-bar">
        <a href="index.php?page=listStudentsInClass<?= ($student && !empty($student['ClassID'])) ? '&classID=' . urlencode($student['ClassID']) : '' ?>" class="btn btn-secondary">&larr; Quay Lại Danh Sách Lớp</a>
    </div>
</div>

<?php if ($student): ?>
    <?php
    $genderBadge = ($student['StudentGender'] === 'Nam') ? 'badge-nam' : 'badge-nu';
    $imagePath   = !empty($student['StudentImage']) ? 'images/' . $student['StudentImage'] : 'images/1.jpg';
    ?>
    <div class="student-detail-card">
        <!-- Khung ảnh đại diện sinh viên -->
        <div class="student-avatar-box">
            <img src="<?= htmlspecialchars($imagePath) ?>" alt="Ảnh sinh viên" onerror="this.src='/src/image/default-avatar.png';">
        </div>

        <!-- Khung thông tin cá nhân -->
        <div class="student-info-box">
            <div class="student-info-row">
                <span class="student-info-label">Mã Sinh Viên:</span>
                <span class="student-info-value"><strong><?= htmlspecialchars($student['ID']) ?></strong></span>
            </div>
            <div class="student-info-row">
                <span class="student-info-label">Họ Và Tên:</span>
                <span class="student-info-value"><?= htmlspecialchars($student['StudentName']) ?></span>
            </div>
            <div class="student-info-row">
                <span class="student-info-label">Giới Tính:</span>
                <span class="student-info-value"><span class="badge <?= $genderBadge ?>"><?= htmlspecialchars($student['StudentGender']) ?></span></span>
            </div>
            <div class="student-info-row">
                <span class="student-info-label">Địa Chỉ:</span>
                <span class="student-info-value"><?= htmlspecialchars($student['StudentAddress']) ?></span>
            </div>
            <div class="student-info-row">
                <span class="student-info-label">Lớp Học:</span>
                <span class="student-info-value">
                    <strong><?= htmlspecialchars($student['ClassID']) ?></strong> - <?= htmlspecialchars($student['ClassName'] ?? '') ?>
                </span>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="table-responsive" style="padding: 20px; text-align: center; color: #ef4444;">
        Không tìm thấy thông tin của sinh viên có mã: <strong><?= htmlspecialchars($studentID) ?></strong>
    </div>
<?php endif; ?>

<?php
// Đóng kết nối sau khi render xong
require_once __DIR__ . '/../libs/closeConnectDB.php';
?>