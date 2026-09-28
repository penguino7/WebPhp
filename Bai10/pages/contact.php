<div class="b10-content-wrap">
    <div class="b10-form-box">
        <div class="b10-form-header"><?= CONTACT_FORM_TITLE ?></div>
        <form method="POST" action="">
            <table class="b10-form-table">
                <tr>
                    <td class="b10-label"><?= LABEL_USERNAME ?></td>
                    <td><input type="text" name="username" class="b10-input" required></td>
                </tr>
                <tr>
                    <td class="b10-label"><?= LABEL_BIRTHDAY ?></td>
                    <td><input type="text" name="birthday" class="b10-input"></td>
                </tr>
                <tr>
                    <td class="b10-label"><?= LABEL_ADDRESS ?></td>
                    <td><input type="text" name="address" class="b10-input"></td>
                </tr>
                <tr>
                    <td class="b10-label"><?= LABEL_MAIL ?></td>
                    <td><input type="email" name="email" class="b10-input"></td>
                </tr>
                <tr>
                    <td class="b10-label"><?= LABEL_PHONE ?></td>
                    <td><input type="text" name="phone" class="b10-input"></td>
                </tr>
                <tr>
                    <td class="b10-label" style="vertical-align: top; padding-top: 6px;"><?= LABEL_COMMENT ?></td>
                    <td><textarea name="comment" class="b10-textarea" rows="4"></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="padding-top: 10px;">
                        <button type="reset" class="b10-btn"><?= BTN_RESET ?></button>
                        <button type="submit" class="b10-btn b10-btn-submit"><?= BTN_SUBMIT ?></button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
