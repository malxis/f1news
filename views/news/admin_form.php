<?php $this->session->flash(); ?>
<?php if (isset($news->idNews)) { ?>
    <a class="btn btn-primary pe-2 mb-2 ms-2" href="<?php echo WEBROOT ?>/news/read/<?= $news->idNews ?>" role="button"><i class="far fa-newspaper"></i> Voir l'article</a>
<?php } ?>

<div class="row pe-2 me-2">
    <div class="col"></div>
    <div class="col-9">

        <?php if (isset($news->idNews)) { ?>
            <h3>Editer un article</h3>
        <?php } else { ?>
            <h3>Ajouter un article</h3>
        <?php } ?>

        <hr>

        <form method="POST" enctype="multipart/form-data" action="<?php echo WEBROOT ?>/news/manage/<?php if (isset($news->idNews)) echo $news->idNews ?>">

            <input type="hidden" name="id" <?php if (isset($news->idNews)) echo 'value="' . $news->idNews . '"' ?>>

            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php if (!empty($_POST["title"])) echo $_POST["title"];
                                                                                        else {
                                                                                            if (isset($news->title)) echo $news->title;
                                                                                        } ?>" required>
            </div>



            <?php if (isset($news->filename)) { ?>
                <div class="mb-3 text-center">
                    <h6>Image actuelle</h6>
                    <img class="img-fluid rounded" src="<?php echo WEBROOT ?>/webroot/img/news/<?php echo $news->newsPicture ?>" alt="<?php echo $news->title ?>">
                </div>
            <?php } ?>
            <div class="input-group mb-3">
                <input type="file" class="form-control" id="mainPicture" name="mainPicture" <?php if (!isset($news->idNews)) echo "required" ?>>
                <label class="input-group-text" for="mainPicture">Charger</label>
            </div>



            <div class="mt-3 mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea name="content" id="editor">
                <?php if (!empty($_POST["content"])) echo $_POST["content"];
                else {
                    if (isset($news->content)) echo $news->content;
                } ?>
            </textarea>
            </div>



            <?php if (isset($news->idNews)) { ?>
                <button type="submit" class="btn btn-primary float-end mb-2">Sauvegarder</button>
            <?php } else { ?>
                <button type="submit" class="btn btn-primary float-end mb-2">Ajouter</button>
            <?php } ?>



        </form>
    </div>
    <div class="col"></div>
</div>

<!-- ckEditor 5 : -->
<script>
    ClassicEditor
        .create(document.querySelector('#editor'));
</script>