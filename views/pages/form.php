<?php $this->session->flash(); ?>
<div class="row pe-2 me-2">
    <div class="col"></div>
    <div class="col-9">

        <?php if (isset($page->idPage)) { ?>
            <h3>Editer une page</h3>
        <?php } else { ?>
            <h3>Ajouter une page</h3>
        <?php } ?>
        <hr>

        <form method="POST" action="<?php echo WEBROOT ?>/pages/manage/<?php if (isset($page->idPage)) echo  $page->idPage ?>">

            <input type="hidden" name="id" <?php if (isset($page->idPage)) echo 'value="' . $page->idPage . '"' ?>>

            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php if (!empty($_POST["title"])) echo $_POST["title"];
                                                                                        else {
                                                                                            if (isset($page->title)) echo $page->title;
                                                                                        } ?>" required>
            </div>

            <div class="mt-3 mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea name="content" id="editor">
                    <?php if (!empty($_POST["content"])) echo $_POST["content"];
                    else {
                        if (isset($page->content)) echo $page->content;
                    } ?>
                </textarea>
            </div>


            <?php if (isset($page->idPage)) { ?>
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