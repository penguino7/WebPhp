<div class="b10-content-wrap">
    <div class="b10-form-box" style="max-width: 400px;">
        <div class="b10-form-header"><?= LOGIN_FORM_TITLE ?></div>
        <form method="POST" action="">
            <table class="b10-form-table">
                <tr>
                    <td class="b10-label"><?= LABEL_USERNAME ?></td>
                    <td><input type="text" name="username" class="b10-input" required></td>
                </tr>
                <tr>
                    <td class="b10-label"><?= LABEL_PASSWORD ?></td>
                    <td><input type="password" name="password" class="b10-input" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="padding-top: 10px;">
                        <button type="reset" class="b10-btn"><?= BTN_RESET ?></button>
                        <button type="submit" class="b10-btn b10-btn-submit"><?= BTN_LOGIN ?></button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
