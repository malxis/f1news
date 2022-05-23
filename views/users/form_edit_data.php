<?php $this->session->flash(); ?>
<form action="<?php echo WEBROOT ?>/users/data" enctype="multipart/form-data" method="POST">
    <div class="row">
        <div class="col"></div>
        <div class="col-9">

            <div class="row">

                <input type="hidden" name="id" <?php echo 'value="' . $_SESSION["user"]->id . '"' ?>>

                <div class="col-md">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" name="email" value="<?= $_SESSION["user"]->email ?>" required>
                        <div id="email_strip" class="mt-1 py-2 w-100" hidden></div>
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">Prénom</label>
                        <input type="text" class="form-control" name="firstName" value="<?= $_SESSION["user"]->firstName ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="birthDate" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" name="birthDate" value="<?= $_SESSION["user"]->birthDate ?>" required>
                    </div>
                </div>

                <div class="col-md">
                    <div class="mb-3">
                        <label for="profilePic" class="form-label">Photo de profil</label>
                        <input class="form-control" type="file" name="profilePic">
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Nom</label>
                        <input type="text" class="form-control" name="lastName" value="<?= $_SESSION["user"]->lastName ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tel" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" name="tel" value="<?= $_SESSION["user"]->tel ?>">
                    </div>
                </div>

                <div><button type="submit" id="submit-btn" class="btn btn-primary float-end">Modifier</button></div>

            </div>
        </div>
        <div class="col"></div>
    </div>
</form>

<script>
    // Validation variable
    var emailFree = false;
    var emailValid = false;

    const emailInput = document.getElementById("email");
    const emailStrip = document.getElementById("email_strip");
    const originalEmail = emailInput.value;

    const submitBtn = document.getElementById("submit-btn");



    function validateForm() {
        if ((emailValid && emailFree) || emailInput.value == originalEmail) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    // Check if the email is free
    emailInput.addEventListener("change", function() {
        if (emailInput.value != originalEmail) {
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
        } else {
            emailStrip.classList.remove("badge", "bg-danger", "bg-success");
            emailStrip.innerHTML = "";
            emailStrip.hidden = true;
        }
        validateForm();
    });

    // Check if the email input value is an email
    emailInput.addEventListener("keyup", function() {
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
    });
</script>