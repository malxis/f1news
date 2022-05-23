<?php $this->session->flash(); ?>
<div class="row">
    <div class="col me-2 ms-2">

        <form action="<?php echo WEBROOT ?>/pages/delete" method="POST">
            <?php if ($elementPerPage > 0) { ?>
                <div class="table-responsive">
                    <table class="table border">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Titre</th>
                                <th scope="col">Posté par</th>
                                <th scope="col" class="text-center">Editer</th>
                                <th scope="col" class="text-center">Supprimer</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($pagesList as $onePageData) {
                            ?>
                                <tr>
                                    <td><input type="checkbox" name="listid[]" value="<?= $onePageData->idPage ?>"></td>

                                    <td><?= $onePageData->title ?></td>
                                    <td><?= $onePageData->firstName ?> <?= $onePageData->lastName ?></td>

                                    <td class="text-center"><a href="<?php echo WEBROOT ?>/pages/manage/<?= $onePageData->idPage ?>"><i class="fas fa-pen"></i></a></td>
                                    <td class="text-center text-primary">
                                        <a href="<?php echo WEBROOT ?>/pages/delete/<?= $onePageData->idPage ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette page ?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p class="text-center text-muted">Aucune page n'a été postée.</p>
            <?php } ?>
    </div>
</div>
<div class="row mb-3">
    <div class="col">

        <?php if ($elementPerPage > 0) { ?>
            <button type="submit" class="btn btn-primary float-end me-2" onclick="return confirm('Voulez-vous vraiment supprimer ces pages ?');">Supprimer</button>
        <?php } ?>

        <a class="btn btn-primary float-end me-2" href="<?php echo WEBROOT ?>/pages/manage" role="button">Ajouter</a>

    </div>
</div>
</form>
<?php
echo $this->tools->pagination($page, $path, $totalElementCount, $elementPerPage);
?>