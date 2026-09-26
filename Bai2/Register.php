<?php include 'Header.php'; ?>

<!-- Nội dung trang Register sẽ được viết tại đây -->
<h3 style="text-align: center; margin-bottom: 15px;">Form Đăng Ký</h3>
<form action="RegisterResult.php" method="POST" class="register-form">

    <div class="form-group">
        <label for="ten">Tên:</label>
        <input type="text" id="ten" name="ten" required>
    </div>
    <div class="form-group">
        <label for="dia_chi">Địa chỉ</label>
        <input type="text" id="dia_chi" name="dia_chi">
    </div>
    <div class="form-group">
        <label for="nghe">Nghề</label>
        <input type="text" id="nghe" name="nghe">
    </div>
    <div class="form-group">
        <label for="ghi_chu">Ghi chú</label>
        <textarea id="ghi_chu" name="ghi_chu" rows="3"></textarea>
    </div>
    <div class="form-buttons">
        <input type="reset" value="Xóa">
        <input type="submit" value="Đăng kí">
    </div>



</form>

<?php include 'Footer.php'; ?>