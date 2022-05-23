<?php $this->session->flash(); ?>
<div class="row">
    <div class="col me-2 ms-2">

        <form action="<?php echo WEBROOT ?>/flashnews/delete" method="POST">
            <?php if ($totalElementCount > 0) { ?>
                <div class="table-responsive">
                    <table class="table border">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Contenu</th>
                                <th scope="col">Date</th>
                                <th scope="col">Posté par</th>
                                <th scope="col" class="text-center">Editer</th>
                                <th scope="col" class="text-center">Supprimer</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($flashNewsList as $flashNews) {
                            ?>
                                <tr>
                                    <td><input type="checkbox" name="listid[]" value="<?= $flashNews->idFlashNews ?>"></td>

                                    <td><?= html_entity_decode($flashNews->content) ?></td>
                                    <td><?= $this->tools->dateEnglishToFrench($flashNews->datePosted) ?></td>
                                    <td><?= $flashNews->firstName ?> <?= $flashNews->lastName ?></td>

                                    <td class="text-center"><a href="<?php echo WEBROOT ?>/flashnews/manage/<?= $flashNews->idFlashNews ?>"><i class="fas fa-pen"></i></a></td>
                                    <td class="text-center text-primary">
                                        <a href="<?php echo WEBROOT ?>/flashnews/delete/<?= $flashNews->idFlashNews ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette flash info ?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p class="text-center text-muted">Aucune flash news n'a été postée.</p>
            <?php } ?>
    </div>
</div>
<div class="row mt-3 mb-3">
    <div class="col">

        <?php if ($totalElementCount > 0) { ?>
            <button type="submit" class="btn btn-primary float-end me-2" onclick="return confirm('Voulez-vous vraiment supprimer ces flash infos ?');">Supprimer</button>
        <?php } ?>

        <a class="btn btn-primary float-end me-2" href="<?php echo WEBROOT ?>/flashnews/manage" role="button">Ajouter</a>

    </div>
</div>
</form>
<?php
echo $this->tools->pagination($page, $path, $totalElementCount, $elementPerPage);
?>