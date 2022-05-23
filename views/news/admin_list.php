<?php $this->session->flash(); ?>
<div class="row">
    <div class="col me-2 ms-2">

        <form action="<?php echo WEBROOT ?>/news/delete" method="POST">
            <?php if ($totalElementCount > 0) { ?>
                <div class="table-responsive">
                    <table class="table border">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Titre</th>
                                <th scope="col">Date</th>
                                <th scope="col" class="text-center">Commentaires</th>
                                <th scope="col" class="text-center">Editer</th>
                                <th scope="col" class="text-center">Supprimer</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            foreach ($newsList as $news) {
                            ?>
                                <tr>
                                    <td><input type="checkbox" name="listid[]" value="<?php echo $news->idNews ?>"></td>

                                    <td><a class="text-decoration-none" href="<?php echo WEBROOT ?>/news/read/<?php echo $news->idNews; ?>"><?php echo $news->title ?></a></td>
                                    <td><?php echo $this->tools->dateEnglishToFrench($news->datePosted) ?></td>

                                    <td class="text-center"><a class="text-decoration-none" href="<?php echo WEBROOT ?>/comments/manage/<?php echo $news->idNews ?>">
                                            <i class="fas fa-check-circle text-success"></i>
                                            <?php echo $news->nbApproved ?>
                                            <i class="fas fa-clock text-warning"></i>
                                            <?php echo $news->nbWaiting ?>
                                            <i class="fas fa-times-circle text-danger"></i>
                                            <?php echo $news->nbRefused ?>
                                        </a></td>

                                    <td class="text-center"><a href="<?php echo WEBROOT ?>/news/manage/<?php echo $news->idNews ?>"><i class="fas fa-pen"></i></a></td>
                                    <td class="text-center text-primary">
                                        <a href="<?php echo WEBROOT ?>/news/delete/<?php echo $news->idNews ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet article ?');"><i class="fas fa-trash"></i></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p class="text-center text-muted">Aucune news n'a été postée.</p>
            <?php } ?>
    </div>
</div>
<div class="row mt-3 mb-3">
    <div class="col">

        <?php if ($totalElementCount > 0) { ?>
            <button type="submit" class="btn btn-primary float-end me-2" onclick="return confirm('Voulez-vous vraiment supprimer ces articles ?');">Supprimer</button>
        <?php } ?>

        <a class="btn btn-primary float-end me-2" href="<?php echo WEBROOT ?>/news/manage" role="button">Ajouter</a>

    </div>
</div>
</form>
<?php
echo $this->tools->pagination($page, $path, $totalElementCount, $elementPerPage);
?>