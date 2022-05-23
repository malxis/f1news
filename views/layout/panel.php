<div class="row" id="panel-aside">




    <div class="col-3 bgcolor-grey mt-n2 mb-n2 me-n2 d-none d-sm-none d-md-none d-lg-block panel-aside-navbar">

        <h5 class="text-center mt-1">Panel Utilisateur</h5>
        <hr class="mb-n1 mt-n1 ms-2 bg-primary">

        <ul class="nav flex-column text-center">
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo WEBROOT ?>/users">Informations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo WEBROOT ?>/contact/requests">Mes demandes</a>
            </li>
        </ul>

        <?php if ($this->session->isAdmin()) { ?>
            <h5 class="text-center mt-1">Panel Administrateur</h5>
            <hr class="mb-n1 mt-n1 ms-2 bg-primary">

            <ul class="nav flex-column text-center">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo WEBROOT ?>/news/admin">Articles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo WEBROOT ?>/flashnews/admin">Flash infos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo WEBROOT ?>/contact/admin">Demandes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo WEBROOT ?>/pages/admin">Pages</a>
                </li>
            </ul>
        <?php } ?>

    </div>






    <div class="offcanvas offcanvas-start bgcolor-grey" tabindex="-1" id="offcanvasPanelAside" aria-labelledby="offcanvasPanelAsideLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasPanelAsideLabel"></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <h5 class="text-center mt-1">Panel Utilisateur</h5>
            <hr class="mb-n1 mt-n1 ms-2 bg-primary">

            <ul class="nav flex-column text-center">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo WEBROOT ?>/users">Informations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo WEBROOT ?>/contact/requests">Mes demandes</a>
                </li>
            </ul>

            <?php if ($this->session->isAdmin()) { ?>
                <h5 class="text-center mt-1">Panel Administrateur</h5>
                <hr class="mb-n1 mt-n1 ms-2 bg-primary">

                <ul class="nav flex-column text-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo WEBROOT ?>/news/admin">Articles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo WEBROOT ?>/flashnews/admin">Flash infos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo WEBROOT ?>/contact/admin">Demandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo WEBROOT ?>/pages/admin">Pages</a>
                    </li>
                </ul>
            <?php } ?>
        </div>
    </div>






    <script>
        var offcanvasElementList = [].slice.call(document.querySelectorAll('.offcanvas'))
        var offcanvasList = offcanvasElementList.map(function(offcanvasEl) {
            return new bootstrap.Offcanvas(offcanvasEl)
        })
    </script>

    <div class="col">
        <div class="row d-xxl-none d-xl-none d-lg-none">
            <div class="col ms-2 mt-1 me-2 mb-2 text-center">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasPanelAside" aria-controls="offcanvasPanelAside">
                    <i class="fas fa-bars"></i> Menu
                </button>
            </div>
        </div>

        <?php echo $content_with_panel; ?>

    </div>





</div>