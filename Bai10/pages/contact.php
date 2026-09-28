<?php
$isSubmitted = false;
$username = '';
$birthday = '';
$address  = '';
$email    = '';
$phone    = '';
$comment  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $comment  = trim($_POST['comment'] ?? '');

    if (!empty($username)) {
        $isSubmitted = true;
    }
}
?>

<div class="b10-content-wrap">
    <div class="b10-form-box">
        <div class="b10-form-header"><?= CONTACT_FORM_TITLE ?></div>

        <?php if ($isSubmitted): ?>
            <div style="background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; padding: 10px 14px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                ✓ <?= MSG_CONTACT_SUCCESS ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_USERNAME ?></label>
                <div class="b10-input-wrap">
                    <input type="text" name="username" class="b10-input" value="<?= htmlspecialchars($username) ?>" required>
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_BIRTHDAY ?></label>
                <div class="b10-input-wrap">
                    <input type="text" name="birthday" class="b10-input" value="<?= htmlspecialchars($birthday) ?>" placeholder="2012-08-12">
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_ADDRESS ?></label>
                <div class="b10-input-wrap">
                    <input type="text" name="address" class="b10-input" value="<?= htmlspecialchars($address) ?>">
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_MAIL ?></label>
                <div class="b10-input-wrap">
                    <input type="email" name="email" class="b10-input" value="<?= htmlspecialchars($email) ?>">
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_PHONE ?></label>
                <div class="b10-input-wrap">
                    <input type="text" name="phone" class="b10-input" value="<?= htmlspecialchars($phone) ?>">
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label" style="align-self: flex-start; padding-top: 6px;"><?= LABEL_COMMENT ?></label>
                <div class="b10-input-wrap">
                    <textarea name="comment" class="b10-textarea" rows="4"><?= htmlspecialchars($comment) ?></textarea>
                </div>
            </div>

            <div class="b10-btn-group">
                <button type="reset" class="b10-btn"><?= BTN_RESET ?></button>
                <button type="submit" class="b10-btn b10-btn-submit"><?= BTN_SUBMIT ?></button>
            </div>
        </form>
    </div>
</div>