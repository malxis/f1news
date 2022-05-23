<?php $this->session->flash(); ?>
<div class="row">
    <div class="col"></div>
    <div class="col d-flex align-items-center">
        <img class="img-fluid rounded-circle mt-3 me-3 ms-3" style="width: 50px;" src="<?php echo WEBROOT ?>/webroot/img/users/<?= $_SESSION["user"]->filename ?>" alt="Photo de profil de <?= $_SESSION["user"]->firstName ?> <?= $_SESSION["user"]->lastName ?>">
        <h3 class="mb-n2"><?= $_SESSION["user"]->firstName ?>
            <?= $_SESSION["user"]->lastName ?></h3>
    </div>
    <div class="col"></div>


    <div class="row mt-3">
        <div class="col text-end">
            <h5>Informations</h5>
            <p class="mb-n1">Né le <?= $this->tools->dateEnglishToFrench($_SESSION["user"]->birthDate) ?></p>
            <?php if (!empty($_SESSION["user"]->tel)) { ?>
                <p class="mb-n1">Téléphone : <?= $_SESSION["user"]->tel ?></p>
            <?php } ?>
            <p class="mb-n1">Inscrit depuis le <?= $this->tools->dateTimeEnglishToFrench($_SESSION["user"]->registerDate) ?></p>
            <p class="mb-n1">Dernière connexion le <?= $this->tools->dateTimeEnglishToFrench($_SESSION["user"]->lastLogIn) ?></p>
        </div>
        <div class="col">
            <h5>Statistiques</h5>
            <?php if ($this->session->isAdmin()) { ?>
                <p class="mb-n1"><?= $newsPostedCount ?> articles postés</p>
            <?php } ?>
            <p class="mb-n1"><?= $commentsPostedCount ?> commentaires postés</p>
            <p class="mb-n1"><?= $contactRequestCount ?> demandes</p>
        </div>
    </div>

    <div class="col text-end me-3">
        <a class="btn btn-primary mt-3" href="<?php echo WEBROOT ?>/users/data" role="button">Modifier mes informations</a>
        <a class="btn btn-primary mt-3 ms-2" href="<?php echo WEBROOT ?>/users/password" role="button">Modifier mon mot de passe</a>
    </div>
</div>