<?php
// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../libs/connectDB.php';

// 2. Lấy mã sinh viên từ GET
$studentID = isset($_GET['id']) ? mysqli_real_escape_string($conn, trim($_GET['id'])) : 'SV001';

// ==========================================================================
// 3. HÀM XỬ LÝ VÀ RENDER THẺ CHI TIẾT SINH VIÊN
// ==========================================================================

/**
 * Render thẻ chi tiết thông tin và ảnh đại diện của sinh viên
 */
function renderStudentDetailCard($conn, $studentID)
{
    $sql = "SELECT S.*, C.ClassName 
            FROM students S 
            LEFT JOIN classes C ON S.ClassID = C.ID 
            WHERE S.ID = '{$studentID}'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $student     = mysqli_fetch_assoc($result);
        $svID        = htmlspecialchars($student['ID']);
        $svName      = htmlspecialchars($student['StudentName']);
        $svGender    = htmlspecialchars($student['StudentGender']);
        $svAddress   = htmlspecialchars($student['StudentAddress']);
        $classID     = htmlspecialchars($student['ClassID']);
        $className   = htmlspecialchars($student['ClassName'] ?? '');
        $genderBadge = ($svGender === 'Nam') ? 'badge-nam' : 'badge-nu';
        $imagePath   = !empty($student['StudentImage']) ? 'images/' . $student['StudentImage'] : 'images/1.jpg';

        echo "
        <div class='student-detail-card'>
            <!-- Khung ảnh đại diện sinh viên -->
            <div class='student-avatar-box'>
                <img src='{$imagePath}' alt='Ảnh {$svName}' onerror=\"this.src='/src/image/default-avatar.png';\">
            </div>

            <!-- Khung thông tin cá nhân -->
            <div class='student-info-box'>
                <div class='student-info-row'>
                    <span class='student-info-label'>Mã Sinh Viên:</span>
                    <span class='student-info-value'><strong>{$svID}</strong></span>
                </div>
                <div class='student-info-row'>
                    <span class='student-info-label'>Họ Và Tên:</span>
                    <span class='student-info-value'>{$svName}</span>
                </div>
                <div class='student-info-row'>
                    <span class='student-info-label'>Giới Tính:</span>
                    <span class='student-info-value'><span class='badge {$genderBadge}'>{$svGender}</span></span>
                </div>
                <div class='student-info-row'>
                    <span class='student-info-label'>Địa Chỉ:</span>
                    <span class='student-info-value'>{$svAddress}</span>
                </div>
                <div class='student-info-row'>
                    <span class='student-info-label'>Lớp Học:</span>
                    <span class='student-info-value'><strong>{$classID}</strong> - {$className}</span>
                </div>
            </div>
        </div>";
    } else {
        echo "
        <div class='table-responsive' style='padding: 20px; text-align: center; color: #ef4444;'>
            Không tìm thấy thông tin của sinh viên có mã: <strong>" . htmlspecialchars($studentID) . "</strong>
        </div>";
    }
}
?>

<!-- GIAO DIỆN: CHI TIẾT SINH VIÊN (pages/studentDetail.php) -->
<div class="page-header">
    <h2 class="page-title">THÔNG TIN CHI TIẾT SINH VIÊN</h2>
    <div class="action-bar">
        <a href="javascript:history.back()" class="btn btn-secondary">&larr; Quay Lại Danh Sách Lớp</a>
    </div>
</div>

<!-- Gọi hàm render thẻ sinh viên duy nhất 1 dòng -->
<?php renderStudentDetailCard($conn, $studentID); ?>

<?php
// Đóng kết nối sau khi render xong
require_once __DIR__ . '/../libs/closeConnectDB.php';
?>