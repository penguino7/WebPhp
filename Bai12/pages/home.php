<?php
// 1. Nhúng file kết nối CSDL
require_once __DIR__ . '/../libs/connectDB.php';

// ==========================================================================
// 2. CÁC HÀM RENDER DANH SÁCH LỚP THEO 3 PHƯƠNG THỨC FETCH
// ==========================================================================

/**
 * Cách 1: Sử dụng mysqli_fetch_row() (Mảng chỉ số số nguyên 0, 1)
 */
function renderClassesFetchRow($conn)
{
    $sql = "SELECT ID, ClassName FROM classes ORDER BY ID ASC";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_row($result)) {
            $maLop = htmlspecialchars($row[0]);
            echo "<li class='class-link-item'><a href='index.php?page=listStudentsInClass&classID={$maLop}'>{$maLop}</a></li>";
        }
    } else {
        echo "<li>Chưa có dữ liệu lớp học!</li>";
    }
}

/**
 * Cách 2: Sử dụng mysqli_fetch_array() (Mảng nhân đôi cả số và chữ)
 */
function renderClassesFetchArray($conn)
{
    $sql = "SELECT ID, ClassName FROM classes ORDER BY ID ASC";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $maLop = htmlspecialchars($row['ID']);
            echo "<li class='class-link-item'><a href='index.php?page=listStudentsInClass&classID={$maLop}'>{$maLop}</a></li>";
        }
    } else {
        echo "<li>Chưa có dữ liệu lớp học!</li>";
    }
}

/**
 * Cách 3: Sử dụng mysqli_fetch_assoc() (Mảng kết hợp Key = Tên cột - Chuẩn tối ưu)
 */
function renderClassesFetchAssoc($conn)
{
    $sql = "SELECT ID, ClassName FROM classes ORDER BY ID ASC";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $maLop = htmlspecialchars($row['ID']);
            echo "<li class='class-link-item'><a href='index.php?page=listStudentsInClass&classID={$maLop}'>{$maLop}</a></li>";
        }
    } else {
        echo "<li>Chưa có dữ liệu lớp học!</li>";
    }
}
?>

<!-- GIAO DIỆN: TRANG CHỦ DANH SÁCH LỚP HỌC (pages/home.php) -->
<div class="page-header">
    <h2 class="page-title">DANH SÁCH CÁC LỚP HỌC (DEMO 3 PHƯƠNG THỨC FETCH DỮ LIỆU)</h2>
</div>

<div class="fetch-demo-container">
    <!-- CÁCH 1: Dùng mysqli_fetch_row() -->
    <div class="fetch-card">
        <div class="fetch-title">Danh Sách Các Lớp (Cách 1: Sử Dụng <code>mysqli_fetch_row()</code>)</div>
        <ul class="class-link-list">
            <?php renderClassesFetchRow($conn); ?>
        </ul>
    </div>

    <!-- CÁCH 2: Dùng mysqli_fetch_array() -->
    <div class="fetch-card">
        <div class="fetch-title">Danh Sách Các Lớp (Cách 2: Sử Dụng <code>mysqli_fetch_array()</code>)</div>
        <ul class="class-link-list">
            <?php renderClassesFetchArray($conn); ?>
        </ul>
    </div>

    <!-- CÁCH 3: Dùng mysqli_fetch_assoc() -->
    <div class="fetch-card">
        <div class="fetch-title">Danh Sách Các Lớp (Cách 3: Sử Dụng <code>mysqli_fetch_assoc()</code>)</div>
        <ul class="class-link-list">
            <?php renderClassesFetchAssoc($conn); ?>
        </ul>
    </div>
</div>

<?php
// Đóng kết nối sau khi render xong
require_once __DIR__ . '/../libs/closeConnectDB.php';
?>