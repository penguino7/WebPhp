<h3 style="text-align: center; margin-bottom: 20px;">Form Đăng ký</h3>

<div class="getform-container">
    <div class="form-header-title">Form Dang ky</div>

    <form name="form1" action="index.php?page=registerProcess" method="POST" class="getform-form">
        <!-- 1. Username -->
        <div class="form-group">
            <label class="form-label">Username:</label>
            <div class="form-control-wrap">
                <input type="text" name="txtUsername" required>
            </div>
        </div>

        <!-- 2. Password -->
        <div class="form-group">
            <label class="form-label">Password:</label>
            <div class="form-control-wrap">
                <input type="password" name="txtPassword" required>
            </div>
        </div>

        <!-- 3. Gender -->
        <div class="form-group">
            <label class="form-label">Gender:</label>
            <div class="form-control-wrap">
                <div class="inline-group">
                    <label><input type="radio" name="radGender" value="Male" checked> Male</label>
                    <label><input type="radio" name="radGender" value="Female"> Female</label>
                </div>
            </div>
        </div>

        <!-- 4. Address -->
        <div class="form-group">
            <label class="form-label">Address:</label>
            <div class="form-control-wrap">
                <select name="lstAddress" size="4">
                    <option value="Ha Noi" selected>Ha Noi</option>
                    <option value="TP. HCM">TP. HCM</option>
                    <option value="Hue">Hue</option>
                    <option value="Da Nang">Da Nang</option>
                </select>
            </div>
        </div>

        <!-- 5. Enable Programming Language -->
        <div class="form-group">
            <label class="form-label">Enable Programming Language:</label>
            <div class="form-control-wrap">
                <div class="inline-group">
                    <label><input type="checkbox" name="chkLang[]" value="PHP"> PHP</label>
                    <label><input type="checkbox" name="chkLang[]" value="C#"> C#</label>
                    <label><input type="checkbox" name="chkLang[]" value="Java"> Java</label>
                    <label><input type="checkbox" name="chkLang[]" value="C++"> C++</label>
                </div>
            </div>
        </div>

        <!-- 6. Skill -->
        <div class="form-group">
            <label class="form-label">Skill:</label>
            <div class="form-control-wrap">
                <div class="block-group">
                    <label><input type="radio" name="radSkill" value="Normal"> Normal</label>
                    <label><input type="radio" name="radSkill" value="Good"> Good</label>
                    <label><input type="radio" name="radSkill" value="Very Good" checked> Very Good</label>
                    <label><input type="radio" name="radSkill" value="Excellent"> Excellent</label>
                </div>
            </div>
        </div>

        <!-- 7. Note -->
        <div class="form-group">
            <label class="form-label">Note:</label>
            <div class="form-control-wrap">
                <textarea name="taNote" rows="3"></textarea>
            </div>
        </div>

        <!-- 8. Marriage Status -->
        <div class="form-group">
            <label class="form-label">Marriage Status:</label>
            <div class="form-control-wrap">
                <label>
                    <input type="checkbox" name="chkMarriageStatus" value="Da ket hon">
                </label>
            </div>
        </div>

        <!-- 9. Form Buttons -->
        <div class="form-buttons">
            <button type="reset" class="btn-reset">Reset</button>
            <button type="submit" name="btnRegister" class="btn-submit">Register</button>
        </div>
    </form>
</div>