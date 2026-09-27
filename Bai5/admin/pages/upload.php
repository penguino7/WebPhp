<h3 style="text-align: center; margin-bottom: 20px;">Trang upload Files</h3>

<?php
$targetDir = __DIR__ . '/../../uploads';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$uploadedFiles = [];
$errors = [];
$isSubmitted = false;

// Xử lý khi nhấn nút Upload
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['btnUpload'])) {
    $isSubmitted = true;

    if (isset($_FILES['files']) && is_array($_FILES['files']['name'])) {
        $totalFiles = count($_FILES['files']['name']);

        for ($i = 0; $i < $totalFiles; $i++) {
            $fileName = $_FILES['files']['name'][$i];
            $tmpName  = $_FILES['files']['tmp_name'][$i];
            $error    = $_FILES['files']['error'][$i];

            if ($error === UPLOAD_ERR_OK && !empty($fileName)) {
                $cleanFileName = basename($fileName);
                $destination = $targetDir . '/' . $cleanFileName;

                if (move_uploaded_file($tmpName, $destination)) {
                    $uploadedFiles[] = $cleanFileName;
                } else {
                    $errors[] = "Không thể lưu file: " . htmlspecialchars($cleanFileName);
                }
            }
        }
    }
}
?>

<div class="admin-upload-form">
    <div class="form-header-title">Chọn file tải lên (Tối đa 5 file)</div>

    <!-- Thông báo kết quả sau khi Upload -->
    <?php if ($isSubmitted): ?>
        <?php if (!empty($uploadedFiles)): ?>
            <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 12px 16px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
                <b>Upload thành công <?= count($uploadedFiles) ?> file:</b>
                <ul style="margin: 8px 0 0 20px; padding: 0;">
                    <?php foreach ($uploadedFiles as $file): ?>
                        <li>
                            <a href="../uploads/<?= rawurlencode($file) ?>" download style="color: #0056b3; font-weight: bold; text-decoration: underline;">
                                <?= htmlspecialchars($file) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px 14px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; text-align: center;">
                <b>Bạn chưa chọn file nào hoặc có lỗi khi tải lên!</b>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div style="color: #dc3545; font-size: 14px; margin-bottom: 15px;">
                <?php foreach ($errors as $err): ?>
                    <p><?= htmlspecialchars($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="admin-upload-list">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <div class="admin-upload-item">
                    <label>File <?= $i ?>:</label>
                    <input type="file" name="files[]">
                </div>
            <?php endfor; ?>
        </div>

        <div class="form-buttons">
            <button type="button" class="btn-reset" onclick="window.location.href='index.php?page=upload'">
                Reset
            </button>
            <button type="submit" name="btnUpload" class="btn-submit">
                Upload
            </button>
        </div>
    </form>
</div>