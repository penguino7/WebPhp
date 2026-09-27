<?php
// Hủy session khi đăng xuất (giữ hoặc xóa cookie tùy chọn)
unset($_SESSION['Username']);
unset($_SESSION['Password']);

// Chuyển hướng quay trở lại trang index của End user
echo "<script>window.location.href='../index.php';</script>";
exit();
