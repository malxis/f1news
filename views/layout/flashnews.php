<div class="container">
    <div class="row">

        <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
            <div class="accordion d-xxl-none d-xl-none d-lg-none mb-3 mt-2" id="accordionNews">

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed bg-primary text-light fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Flash Infos
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionNews">
                        <ul class="accordion-body list-group list-group-flush p-0">
                            <?php foreach ($flashNewsList as $flashNews) { ?>
                                <li class="list-group-item">
                                    <?php echo html_entity_decode($flashNews->content); ?><br>
                                    <small class="text-muted float-end mt-n4"><?php echo $this->tools->dateEnglishToFrench($flashNews->datePosted); ?></small>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed bg-primary text-light fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Prochaines courses
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionNews">
                        <ul class="accordion-body list-group list-group-flush p-0 race-coming">
                        </ul>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed bg-primary text-light fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Dernières courses
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionNews">
                        <ul class="accordion-body list-group list-group-flush p-0 race-finished">
                        </ul>
                    </div>
                </div>

            </div>

            <?php echo $content_with_flashnews; ?>

        </div>

        <div class="col-3 ms-n1 d-none d-sm-none d-md-none d-lg-block">

            <div class="border rounded mt-2">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-primary text-center text-light fw-bold">Flash Infos</li>

                    <?php foreach ($flashNewsList as $flashNews) { ?>
                        <li class="list-group-item">
                            <?php echo html_entity_decode($flashNews->content); ?><br>
                            <small class="text-muted float-end mt-n4"><?php echo $this->tools->dateEnglishToFrench($flashNews->datePosted); ?></small>
                        </li>
                    <?php } ?>

                </ul>
            </div>

            <div class="border rounded mt-3">
                <ul class="list-group list-group-flush race-coming">
                    <li class="list-group-item bg-primary text-center text-light fw-bold">Prochaines courses</li>

                </ul>
            </div>

            <div class="border rounded mt-3">
                <ul class="list-group list-group-flush race-finished">
                    <li class="list-group-item bg-primary text-center text-light fw-bold">Dernières courses</li>

                </ul>
            </div>

        </div>

    </div>
</div>

<script src="<?php echo WEBROOT ?>/webroot/js/tools/tools.js"></script>
<script src="<?php echo WEBROOT ?>/webroot/js/f1/news-aside-races-info.js"></script>