<?php include 'Header.php'; ?>

<!-- Nội dung trang RegisterResult sẽ được viết tại đây -->
<h3 style="text-align: center; margin-bottom: 15px;">Kết quả đăng ký</h3>


<?php
if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $ten = $_POST['ten'] ?? 'Empty';
    $diachi = $_POST['dia_chi'] ?? 'Empty';
    $nghe = $_POST['nghe'] ?? 'Empty';
    $ghichu = $_POST['ghi_chu'] ?? 'Empty';



    echo '
<div class="result-box">
    <p><strong>Tên:</strong> ' . htmlspecialchars($ten) . '</p>
    <p><strong>Địa chỉ:</strong> ' . htmlspecialchars($diachi) . '</p>
    <p><strong>Nghề:</strong> ' . htmlspecialchars($nghe) . '</p>
    <p><strong>Ghi chú:</strong> ' . nl2br(htmlspecialchars($ghichu)) . '</p>
</div>';
} else {
    echo " <p>
    Chưa đăng kí
    </p>";
}


?>

<?php include 'Footer.php'; ?>