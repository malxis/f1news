<?php $this->session->flash(); ?>
<div class="container">
    <div class="row">

        <div class="container">

            <form method="POST" action="<?php echo WEBROOT ?>/users/register">

                <div class="row">
                    <div class="col-md mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" <?php if (!empty($_POST["email"])) echo 'value="' . $_POST["email"] . '"'; ?> required>
                        <div id="email_strip" class="mt-1 py-2 w-100" hidden></div>
                    </div>
                    <div class="col-md mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div id="password_strip" class="mt-1 py-2" hidden></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                    </div>
                    <div class="col-md mb-3">
                        <label for="confirmPassword" class="form-label">Confirmer votre mot de passe</label>
                        <input type="password" class="form-control" id="confirm_password" required>
                        <div id="confirm_password_strip" class="mt-1 py-2 w-100" hidden></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md mb-3">
                        <label for="firstName" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" <?php if (!empty($_POST["firstName"])) echo 'value="' . $_POST["firstName"] . '"'; ?> required>
                    </div>
                    <div class="col-md mb-3">
                        <label for="lastName" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" <?php if (!empty($_POST["lastName"])) echo 'value="' . $_POST["lastName"] . '"'; ?> required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md mb-3">
                        <label for="birthDate" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="birthDate" name="birthDate" <?php if (!empty($_POST["birthDate"])) echo 'value="' . $_POST["birthDate"] . '"'; ?> required>
                    </div>
                    <div class="col-md mb-3">
                        <label for="tel" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="tel" name="tel" <?php if (!empty($_POST["tel"])) echo 'value="' . $_POST["tel"] . '"'; ?>>
                    </div>
                </div>
                <div>
                    <div class="g-recaptcha" data-sitekey="6Lc5RNoaAAAAAHnT3nFba8pwf248kOtUItTYrHIQ"></div>
                    <?php if (isset($invalidCaptcha)) { ?>
                        <div class="mt-1 py-2 badge bg-danger">Veuillez valider le captcha</div>
                    <?php } ?>
                </div>
                <button type="submit" class="btn btn-secondary mt-3" id="submit-btn" disabled>S'inscrire</button>
            </form>

        </div>

    </div>
</div>

<script src="<?php echo WEBROOT ?>/webroot/js/tools/password-strenght-checker.js"></script>
<script>
    window.simple_password_checker.init({
        id_password: "password",
        id_strip: "password_strip"
    });

    // validation variable
    var emailFree = false;
    var emailValid = false;
    var equalPasswords = false;

    const emailInput = document.getElementById("email");
    const emailStrip = document.getElementById("email_strip");

    const passwordInput = document.getElementById("password");

    const confirmPasswordInput = document.getElementById("confirm_password");
    const confirmPasswordStrip = document.getElementById("confirm_password_strip");

    const submitBtn = document.getElementById("submit-btn");



    function validateForm() {
        if (window.simple_password_checker.passwordValid && equalPasswords && emailValid && emailFree) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    function arePasswordsEqual() {
        confirmPasswordStrip.hidden = false;
        if (confirmPasswordInput.value === passwordInput.value) {
            equalPasswords = true;
            confirmPasswordStrip.classList.remove("badge", "bg-danger");
            confirmPasswordStrip.classList.add("badge", "bg-success");
            confirmPasswordStrip.innerHTML = "<i class='fa-solid fa-circle-check'></i> Les mots de passe correspondent";
        } else {
            equalPasswords = false;
            confirmPasswordStrip.classList.remove("badge", "bg-success");
            confirmPasswordStrip.classList.add("badge", "bg-danger");
            confirmPasswordStrip.innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Les mots de passe ne correspondent pas";
        }
        validateForm();
    }

    // Check if the email is free
    function isEmailFree() {
        if (emailValid) {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "<?php echo WEBROOT ?>/users/is_email_free", true);
            xhttp.setRequestHeader("Content-Type", "application/json");
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Return 1 if the email is free and 0 if it's taken
                    emailFree = parseInt(this.responseText);
                    emailStrip.hidden = false;
                    if (emailFree == 1) {
                        emailStrip.classList.remove("badge", "bg-danger");
                        emailStrip.classList.add("badge", "bg-success");
                        emailStrip.innerHTML = "<i class='fa-solid fa-circle-check'></i> Cette adresse mail est libre";
                    } else {
                        emailStrip.classList.remove("badge", "bg-success");
                        emailStrip.classList.add("badge", "bg-danger");
                        emailStrip.innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Cette adresse mail est déjà utilisée";
                    }
                    validateForm();
                }
            };
            var data = {
                email: emailInput.value
            };
            xhttp.send(JSON.stringify(data));
        }
        validateForm();
    }

    // Check if the email input value is an email
    function isEmailValid() {
        emailStrip.hidden = false;
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailInput.value)) {
            emailValid = true;
        } else {
            emailValid = false;
            emailStrip.classList.remove("badge", "bg-success");
            emailStrip.classList.add("badge", "bg-danger");
            emailStrip.innerHTML = "<i class='fa-solid fa-circle-xmark'></i> Cette adresse mail n'est pas valide";
        }
        validateForm();
    }


    emailInput.addEventListener("change", function() {
        isEmailFree();
    });

    emailInput.addEventListener("keyup", function() {
        isEmailValid();
    });

    // Check if the confirm password value is equal to the password value
    confirmPasswordInput.addEventListener("keyup", function() {
        arePasswordsEqual();
    });

    passwordInput.addEventListener("keyup", function() {
        arePasswordsEqual();
    });

    isEmailValid();
    isEmailFree();
</script>