<?php $this->session->flash(); ?>
<?php
if (isset($newsList)) {
    foreach ($newsList as $news) { ?>

        <div class="card mb-3 mt-2">
            <img src="<?php echo WEBROOT ?>/webroot/img/news/<?= $news->newsPicture ?>" class="card-img-top" alt="<?= $news->imageLink ?>">
            <div class="card-body">
                <h5 class="card-title text-uppercase fw-bold"><?= $news->title ?></h5>
                <p class="card-text text-truncate"><?= strip_tags(html_entity_decode($news->content)) ?></p>
                <div class="float-end">
                    <p class="card-text"><small class="text-muted">Publié le <?= $this->tools->dateTimeEnglishToFrench($news->datePosted) ?> par <?= $news->firstName ?> <?= $news->lastName ?></small></p>
                    <a href="<?php echo WEBROOT ?>/news/read/<?= $news->idNews ?>" class="btn btn-secondary float-end">En savoir plus</a>
                </div>
            </div>
        </div>

<?php
    }
    echo $this->tools->pagination($page, $path, $totalElementCount, $elementPerPage);
}
?>