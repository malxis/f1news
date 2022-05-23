<?php $this->session->flash(); ?>
<div class="row">
    <div class="col"></div>
    <div class="col-9">

        <form action="<?php echo WEBROOT ?>/users/password" method="POST">

            <div class="mb-3">
                <label for="old_password" class="form-label">Ancien mot de passe</label>
                <input type="password" class="form-control" id="old_password" name="old_password" required>
                <div id="old_password_strip" class="mt-1 py-2" hidden></div>
            </div>

            <div class="mb-3">
                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
                <div id="new_password_strip" class="mt-1 py-2" hidden></div>
            </div>

            <div class="mb-3">
                <label for="confirm_new_password" class="form-label">Confirmer votre nouveau mot de passe</label>
                <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                <div id="confirm_new_password_strip" class="mt-1 py-2 w-100" hidden></div>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-primary" disabled>Modifier</button>
        </form>
    </div>
    <div class="col"></div>
</div>

<script src="<?php echo WEBROOT ?>/webroot/js/tools/password-strenght-checker.js"></script>
<script src="<?php echo WEBROOT ?>/webroot/js/tools/md5.js"></script>
<script>
    window.simple_password_checker.init({
        id_password: "new_password",
        id_strip: "new_password_strip"
    });

    // Validation variable
    var equalPasswords = false;
    var passwordIsCorrect = 0;

    const oldPasswordInput = document.getElementById("old_password");
    const oldPasswordStrip = document.getElementById("old_password_strip");

    const newPasswordInput = document.getElementById("new_password");

    const confirmNewPasswordInput = document.getElementById("confirm_new_password");
    const confirmNewPasswordStrip = document.getElementById("confirm_new_password_strip");

    const submitBtn = document.getElementById("submit-btn");

    function validateForm() {
        if (window.simple_password_checker.passwordValid && equalPasswords && passwordIsCorrect) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    function arePasswordsEqual() {
        confirmNewPasswordStrip.hidden = false;
        if (confirmNewPasswordInput.value === newPasswordInput.value && confirmNewPasswordInput.value != "") {
            equalPasswords = true;
            confirmNewPasswordStrip.classList.remove("badge", "bg-danger");
            confirmNewPasswordStrip.classList.add("badge", "bg-success");
            confirmNewPasswordStrip.innerHTML = "<i class='fa-solid fa-circle-check'></i> Les nouveaux mots de passe correspondent";
        } else {
            equalPasswords = false;
            confirmNewPasswordStrip.classList.remove("badge", "bg-success");
            confirmNewPasswordStrip.classList.add("badge", "bg-danger");
            confirmNewPasswordStrip.innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Les nouveaux mots de passe ne correspondent pas";
        }
        validateForm();
    }

    oldPasswordInput.addEventListener("change", function() {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "<?php echo WEBROOT ?>/users/is_password_correct", true);
        xhttp.setRequestHeader("Content-Type", "application/json");
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Return 1 if the password is correct and 0 if it isn't
                passwordIsCorrect = parseInt(this.responseText);
                oldPasswordStrip.hidden = false;
                if (passwordIsCorrect == 1) {
                    oldPasswordStrip.classList.remove("badge", "bg-danger");
                    oldPasswordStrip.classList.add("badge", "bg-success");
                    oldPasswordStrip.innerHTML = "<i class='fa-solid fa-circle-check'></i> Votre ancien mot de passe est correct";
                } else {
                    oldPasswordStrip.classList.remove("badge", "bg-success");
                    oldPasswordStrip.classList.add("badge", "bg-danger");
                    oldPasswordStrip.innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Votre ancien mot de passe est incorrect";
                }
                validateForm();
            }
        };
        var data = {
            password: MD5(oldPasswordInput.value),
            userId: <?php echo $_SESSION["user"]->id ?>
        };
        xhttp.send(JSON.stringify(data));
        validateForm();
    });

    // Check if the confirm password value is equal to the password value
    confirmNewPasswordInput.addEventListener("keyup", function() {
        arePasswordsEqual();
    });

    newPasswordInput.addEventListener("keyup", function() {
        arePasswordsEqual();
    });
</script>