<?php $this->session->flash(); ?>
<div class="container">
    <div class="row">

        <div class="col"></div>

        <div class="col-8">

            <form action="<?php echo WEBROOT ?>/users/login" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" <?php if (!empty($_POST["email"])) echo 'value="' . $_POST["email"] . '"'; ?> required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-secondary">Se connecter</button>

                <p class="text-center mt-3"><b>DÉMO</b> - Informations de connexion :<br>
                    Compte administrateur : sanchez.alex@gmail.com<br>
                    Compte utilisateur : alexis.mathieu79@gmail.com<br><br>

                    Mot de passe : motdepasse</p>
            </form>

            <hr>

            <div class="text-center">
                <p class="fw-bold">Pas encore inscrit ?</p>

                <a href="<?php echo WEBROOT ?>/users/register" class="btn btn-secondary">S'inscrire</a>
            </div>

        </div>

        <div class="col"></div>

    </div>
</div>