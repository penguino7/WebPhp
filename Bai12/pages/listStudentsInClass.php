<?php
// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../libs/connectDB.php';

// 2. Lấy mã lớp từ GET
$classID = isset($_GET['classID']) ? mysqli_real_escape_string($conn, trim($_GET['classID'])) : 'PHP001';

// 3. Lấy thông tin chi tiết của lớp học
$sql_class = "SELECT * FROM classes WHERE ID = '{$classID}'";
$res_class = mysqli_query($conn, $sql_class);
$classInfo = ($res_class && mysqli_num_rows($res_class) > 0) ? mysqli_fetch_assoc($res_class) : null;
$className = $classInfo ? $classInfo['ClassName'] : $classID;

// 4. Truy vấn danh sách sinh viên thuộc lớp này
$sql_students = "SELECT * FROM students WHERE ClassID = '{$classID}' ORDER BY ID ASC";
$res_students = mysqli_query($conn, $sql_students);
?>

<!-- GIAO DIỆN: DANH SÁCH SINH VIÊN TRONG LỚP (pages/listStudentsInClass.php) -->
<div class="page-header">
    <h2 class="page-title">
        DANH SÁCH SINH VIÊN TRONG LỚP: <span style="color: #2563eb;"><?= htmlspecialchars($classID) ?> - <?= htmlspecialchars($className) ?></span>
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
            <?php
            if ($res_students && mysqli_num_rows($res_students) > 0) {
                $stt = 1;
                while ($student = mysqli_fetch_assoc($res_students)) {
                    $svID      = htmlspecialchars($student['ID']);
                    $svName    = htmlspecialchars($student['StudentName']);
                    $svAddress = htmlspecialchars($student['StudentAddress']);
                    $svGender  = htmlspecialchars($student['StudentGender']);
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
            ?>
        </tbody>
    </table>
</div>

<?php
// Đóng kết nối sau khi render xong
require_once __DIR__ . '/../libs/closeConnectDB.php';
?>