<div class="b10-content-wrap">
    <div class="b10-form-box" style="max-width: 400px;">
        <div class="b10-form-header"><?= LOGIN_FORM_TITLE ?></div>
        <form method="POST" action="">
            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_USERNAME ?></label>
                <div class="b10-input-wrap">
                    <input type="text" name="username" class="b10-input" required>
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_PASSWORD ?></label>
                <div class="b10-input-wrap">
                    <input type="password" name="password" class="b10-input" required>
                </div>
            </div>

            <div class="b10-btn-group">
                <button type="reset" class="b10-btn"><?= BTN_RESET ?></button>
                <button type="submit" class="b10-btn b10-btn-submit"><?= BTN_LOGIN ?></button>
            </div>
        </form>
    </div>
</div>