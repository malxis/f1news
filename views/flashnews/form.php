<?php $this->session->flash(); ?>
<div class="row pe-2 me-2">
    <div class="col"></div>
    <div class="col-9">

        <?php if (isset($flashNews->idFlashNews)) { ?>
            <h3>Editer une flash info</h3>
        <?php } else { ?>
            <h3>Ajouter une flash info</h3>
        <?php } ?>
        <hr>

        <form method="POST" action="<?php echo WEBROOT ?>/flashnews/manage/<?php if (isset($flashNews->idFlashNews)) echo  $flashNews->idFlashNews ?>">

            <input type="hidden" name="id" <?php if (isset($flashNews->idFlashNews)) echo 'value="' . $flashNews->idFlashNews . '"' ?>>

            <div class="mt-3 mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea name="content" id="editor">
                    <?php if (!empty($_POST["content"])) echo $_POST["content"];
                    else {
                        if (isset($flashNews->content)) echo $flashNews->content;
                    } ?>
                </textarea>
            </div>


            <?php if (isset($flashNews->idFlashNews)) { ?>
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