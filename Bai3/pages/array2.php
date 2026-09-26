<h3 style="text-align: center; margin-bottom: 5px;">Sử dụng mảng kết hợp</h3>

<form method="POST" action="index.php?page=uploadprocess" enctype="multipart/form-data" class="upload-form">
    <div class="upload-list">
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <div class="upload-item">
                <label>File <?= $i ?>:</label>
                <input type="file" name="files[]">
            </div>
        <?php endfor; ?>
    </div>

    <div class="form-buttons">
        <button type="reset" class="btn-reset">Reset</button>
        <button type="submit" name="submit" class="btn-submit">Upload</button>
    </div>
</form>