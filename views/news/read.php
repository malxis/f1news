<?php $this->session->flash(); ?>
<?php
if (isset($news)) { ?>
    <div class="card mt-2 mb-3">
        <h3 class="text-center mb-n2 mt-1"><?= $news->title ?></h3>

        <hr class="bg-secondary">
        <img src="<?php echo WEBROOT ?>/webroot/img/news/<?= $news->newsPicture ?>" class="img-fluid rounded mx-auto d-block mt-n3 mb-n3" alt="<?= $news->imageLink ?>">
        <hr class="bg-secondary">

        <div class="card-body">
            <?= html_entity_decode($news->content); ?>
        </div>

        <p class="d-flex align-items-center justify-content-end">
            <small class="text-muted me-3 ms-3">
                Publié le <?= $this->tools->dateTimeEnglishToFrench($news->datePosted) ?> par <?= $news->firstName ?> <?= $news->lastName ?>
            </small>
            <img class="img-fluid rounded-circle me-3" style="width: 50px;" src="<?php echo WEBROOT ?>/webroot/img/users/<?= $news->filename ?>" alt="Photo de profil de <?= $news->firstName ?> <?= $news->lastName ?>">
        </p>
    </div>

    <?php
    if ($comments) {
        foreach ($comments as $comment) { ?>
            <div class="card mb-3">
                <div class="row">
                    <div class="col d-flex align-items-center">
                        <img class="img-fluid rounded-circle mt-3 me-3 ms-3" style="width: 50px;" src="<?php echo WEBROOT ?>/webroot/img/users/<?= $comment->filename ?>" alt="Photo de profil de <?= $comment->firstName ?> <?= $comment->lastName ?>">
                        <div class="row">
                            <h5 class="card-title mt-3">Commentaire de <?= $comment->firstName ?> <?= $comment->lastName ?></h5>
                            <small class="text-muted mt-n2">Posté le <?= $this->tools->dateTimeEnglishToFrench($comment->datePosted) ?></small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?= html_entity_decode($comment->content); ?>
                </div>
            </div>
    <?php
        }
    } else {
        echo '<p class="text-center text-muted">Aucun commentaire</p>';
    }

    echo $this->tools->pagination($page, $path, $totalElementCount, $elementPerPage);

    ?>
    <hr>

    <?php
    if ($this->session->isLogged()) { ?>

        <h5>Ecrire un commentaire</h5>

        <form action="<?php echo WEBROOT ?>/news/read/<?php echo $news->idNews . "/" . $page ?>" method="POST">

            <textarea name="content" id="editor"><? if (isset($_POST["content"])) echo $_POST["content"]; ?>
            </textarea>

            <div>
                <div class="g-recaptcha mt-2" data-sitekey="6Lc5RNoaAAAAAHnT3nFba8pwf248kOtUItTYrHIQ"></div>
                <?php if (isset($invalidCaptcha)) { ?>
                    <div class="mt-1 py-2 badge bg-danger">Veuillez valider le captcha</div>
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Envoyer</button>
        </form>

    <?php
    } else {
        echo '<p class="text-center text-muted">Vous devez être connecté pour écrire un commentaire.</p>';
    } ?>

<?php
}
?>

<!-- ckEditor 5 -->
<script>
    ClassicEditor
        .create(document.querySelector('#editor'));
</script>