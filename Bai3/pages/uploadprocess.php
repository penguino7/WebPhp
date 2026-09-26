<h3 style="text-align: center; margin-bottom: 20px;">Danh sách file đã upload</h3>

<div class="upload-result-container">
    <?php
    $targetDir = __DIR__ . '/../uploads';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $uploadedFiles = [];
    $errors = [];

    if (isset($_FILES['files']) && is_array($_FILES['files']['name'])) {
        $totalFiles = count($_FILES['files']['name']);

        for ($i = 0; $i < $totalFiles; $i++) {
            $fileName = $_FILES['files']['name'][$i];
            $tmpName  = $_FILES['files']['tmp_name'][$i];
            $error    = $_FILES['files']['error'][$i];

            // Nếu người dùng có chọn file và upload lên thư mục tạm không có lỗi
            if ($error === UPLOAD_ERR_OK && !empty($fileName)) {
                $cleanFileName = basename($fileName);
                $destination = $targetDir . '/' . $cleanFileName;

                // Di chuyển file từ thư mục tạm vào thư mục uploads/
                if (move_uploaded_file($tmpName, $destination)) {
                    $uploadedFiles[] = $cleanFileName;
                } else {
                    $errors[] = "Không thể lưu file: " . htmlspecialchars($cleanFileName);
                }
            }
        }
    }
    ?>

    <?php if (!empty($uploadedFiles)): ?>
        <ul class="upload-result-list">
            <?php foreach ($uploadedFiles as $file): ?>
                <li>
                    <a href="uploads/<?= rawurlencode($file) ?>" download>
                        Download File: <?= htmlspecialchars($file) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="text-align: center; color: #dc3545; font-weight: bold; margin-bottom: 15px;">
            Chưa có file nào được upload thành công hoặc bạn chưa chọn file nào!
        </p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div style="color: #dc3545; margin-bottom: 15px;">
            <?php foreach ($errors as $err): ?>
                <p><?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 25px;">
        <a href="index.php?page=array2" class="btn-back-upload">
            ← Quay lại trang Upload
        </a>
    </div>
</div>