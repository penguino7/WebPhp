<div class="b10-content-wrap">
    <div class="b10-form-box">
        <div class="b10-form-header"><?= CONTACT_FORM_TITLE ?></div>
        <form method="POST" action="">
            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_USERNAME ?></label>
                <div class="b10-input-wrap">
                    <input type="text" name="username" class="b10-input" required>
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_BIRTHDAY ?></label>
                <div class="b10-input-wrap">
                    <input type="text" name="birthday" class="b10-input">
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_ADDRESS ?></label>
                <div class="b10-input-wrap">
                    <input type="text" name="address" class="b10-input">
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_MAIL ?></label>
                <div class="b10-input-wrap">
                    <input type="email" name="email" class="b10-input">
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label"><?= LABEL_PHONE ?></label>
                <div class="b10-input-wrap">
                    <input type="text" name="phone" class="b10-input">
                </div>
            </div>

            <div class="b10-form-group">
                <label class="b10-label" style="align-self: flex-start; padding-top: 6px;"><?= LABEL_COMMENT ?></label>
                <div class="b10-input-wrap">
                    <textarea name="comment" class="b10-textarea" rows="4"></textarea>
                </div>
            </div>

            <div class="b10-btn-group">
                <button type="reset" class="b10-btn"><?= BTN_RESET ?></button>
                <button type="submit" class="b10-btn b10-btn-submit"><?= BTN_SUBMIT ?></button>
            </div>
        </form>
    </div>
</div>