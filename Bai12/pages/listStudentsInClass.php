<?php
// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../libs/connectDB.php';

// 2. Lấy mã lớp từ GET
$classID = isset($_GET['classID']) ? mysqli_real_escape_string($conn, trim($_GET['classID'])) : 'PHP001';

// ==========================================================================
// 3. CÁC HÀM XỬ LÝ DỮ LIỆU
// ==========================================================================

/**
 * Lấy tên lớp học dựa vào mã lớp
 */
function getClassNameById($conn, $classID)
{
    $sql = "SELECT ClassName FROM classes WHERE ID = '{$classID}'";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return htmlspecialchars($row['ClassName']);
    }
    return htmlspecialchars($classID);
}

/**
 * Render bảng danh sách sinh viên thuộc lớp được chọn
 */
function renderStudentsInClassTable($conn, $classID)
{
    $sql = "SELECT * FROM students WHERE ClassID = '{$classID}' ORDER BY ID ASC";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $stt = 1;
        while ($student = mysqli_fetch_assoc($result)) {
            $svID       = htmlspecialchars($student['ID']);
            $svName     = htmlspecialchars($student['StudentName']);
            $svAddress  = htmlspecialchars($student['StudentAddress']);
            $svGender   = htmlspecialchars($student['StudentGender']);
            $badgeClass = ($svGender === 'Nam') ? 'badge-nam' : 'badge-nu';

            echo "
            <tr>
                <td class='text-center'>" . ($stt++) . "</td>
                <td><strong>{$svName}</strong> ({$svID})</td>
                <td>{$svAddress}</td>
                <td class='text-center'><span class='badge {$badgeClass}'>{$svGender}</span></td>
                <td class='text-center'>
                    <a href='index.php?page=studentDetail&id={$svID}' class='btn btn-sm btn-primary'>Chi Tiết</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5' class='text-center'>Lớp học này hiện chưa có sinh viên nào!</td></tr>";
    }
}
?>

<!-- GIAO DIỆN: DANH SÁCH SINH VIÊN TRONG LỚP (pages/listStudentsInClass.php) -->
<div class="page-header">
    <h2 class="page-title">
        DANH SÁCH SINH VIÊN TRONG LỚP: <span style="color: #2563eb;"><?= htmlspecialchars($classID) ?> - <?= getClassNameById($conn, $classID) ?></span>
    </h2>
    <div class="action-bar">
        <a href="index.php?page=home" class="btn btn-secondary">&larr; Quay Lại Danh Sách Lớp</a>
    </div>
</div>

<div class="table-responsive">
    <table class="custom-table">
        <thead>
            <tr>
                <th width="80" class="text-center">STT</th>
                <th>Tên Sinh Viên</th>
                <th>Địa Chỉ</th>
                <th width="120" class="text-center">Giới Tính</th>
                <th width="140" class="text-center">Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            <!-- Gọi hàm render duy nhất 1 dòng -->
            <?php renderStudentsInClassTable($conn, $classID); ?>
        </tbody>
    </table>
</div>

<?php
// Đóng kết nối sau khi render xong
require_once __DIR__ . '/../libs/closeConnectDB.php';
?>