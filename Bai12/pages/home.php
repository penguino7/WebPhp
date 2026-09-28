<?php
// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../libs/connectDB.php';
?>

<!-- GIAO DIỆN: TRANG CHỦ DANH SÁCH LỚP HỌC (pages/home.php) -->
<div class="page-header">
    <h2 class="page-title">DANH SÁCH CÁC LỚP HỌC (DEMO 3 PHƯƠNG THỨC FETCH DỮ LIỆU)</h2>
</div>

<div class="fetch-demo-container">
    <!-- CÁCH 1: Dùng mysqli_fetch_row() (Mảng chỉ số số nguyên 0, 1) -->
    <div class="fetch-card">
        <div class="fetch-title">Danh Sách Các Lớp (Cách 1: Sử Dụng <code>mysqli_fetch_row()</code>)</div>
        <ul class="class-link-list">
            <?php
            $sql1 = "SELECT ID, ClassName FROM classes ORDER BY ID ASC";
            $res1 = mysqli_query($conn, $sql1);
            if ($res1 && mysqli_num_rows($res1) > 0) {
                while ($row1 = mysqli_fetch_row($res1)) {
                    // Truy cập bằng chỉ số số nguyên: $row1[0] là ID, $row1[1] là ClassName
                    $maLop = htmlspecialchars($row1[0]);
                    echo "<li class='class-link-item'><a href='index.php?page=listStudentsInClass&classID={$maLop}'>{$maLop}</a></li>";
                }
            } else {
                echo "<li>Chưa có dữ liệu lớp học!</li>";
            }
            ?>
        </ul>
    </div>

    <!-- CÁCH 2: Dùng mysqli_fetch_array() (Mảng nhân đôi cả số và tên cột) -->
    <div class="fetch-card">
        <div class="fetch-title">Danh Sách Các Lớp (Cách 2: Sử Dụng <code>mysqli_fetch_array()</code>)</div>
        <ul class="class-link-list">
            <?php
            $sql2 = "SELECT ID, ClassName FROM classes ORDER BY ID ASC";
            $res2 = mysqli_query($conn, $sql2);
            if ($res2 && mysqli_num_rows($res2) > 0) {
                while ($row2 = mysqli_fetch_array($res2)) {
                    // Truy cập bằng cả key chữ hoặc key số đều được: $row2['ID'] hoặc $row2[0]
                    $maLop = htmlspecialchars($row2['ID']);
                    echo "<li class='class-link-item'><a href='index.php?page=listStudentsInClass&classID={$maLop}'>{$maLop}</a></li>";
                }
            } else {
                echo "<li>Chưa có dữ liệu lớp học!</li>";
            }
            ?>
        </ul>
    </div>

    <!-- CÁCH 3: Dùng mysqli_fetch_assoc() (Mảng kết hợp Key = Tên cột - Chuẩn mực tối ưu) -->
    <div class="fetch-card">
        <div class="fetch-title">Danh Sách Các Lớp (Cách 3: Sử Dụng <code>mysqli_fetch_assoc()</code>)</div>
        <ul class="class-link-list">
            <?php
            $sql3 = "SELECT ID, ClassName FROM classes ORDER BY ID ASC";
            $res3 = mysqli_query($conn, $sql3);
            if ($res3 && mysqli_num_rows($res3) > 0) {
                while ($row3 = mysqli_fetch_assoc($res3)) {
                    // Truy cập bằng chính xác tên cột trong CSDL: $row3['ID'], $row3['ClassName']
                    $maLop = htmlspecialchars($row3['ID']);
                    echo "<li class='class-link-item'><a href='index.php?page=listStudentsInClass&classID={$maLop}'>{$maLop}</a></li>";
                }
            } else {
                echo "<li>Chưa có dữ liệu lớp học!</li>";
            }
            ?>
        </ul>
    </div>
</div>

<?php
// Đóng kết nối sau khi render xong
require_once __DIR__ . '/../libs/closeConnectDB.php';
?>