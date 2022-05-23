<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php if (isset($title)) {
            echo $title;
        } else { ?>
            F1 News
        <?php } ?>
    </title>

    <link rel="stylesheet" href="<?php echo WEBROOT ?>/webroot/css/style.css">
    <link rel="shortcut icon" href="<?php echo WEBROOT ?>/webroot/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/27.0.0/classic/ckeditor.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
    <header class="row" id="header">
        <nav class="navbar navbar-expand-md navbar-dark bg-secondary pb-2">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo WEBROOT ?>/">
                    <img src="<?php echo WEBROOT ?>/webroot/img/logo.png" style="width: 100px;" alt="F1 News">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo WEBROOT ?>/">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo WEBROOT ?>/news">Actualités</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo WEBROOT ?>/browse/calendar">Calendrier</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo WEBROOT ?>/contact">Contactez-nous</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <?php if ($this->session->isLogged()) { ?>
                                <div class="dropdown dropstart">
                                    <a id="dropdownNavbarUser" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img class="img-fluid rounded-circle" style="width: 40px;" src="<?php echo WEBROOT ?>/webroot/img/users/<?= $_SESSION["user"]->filename ?>" alt="Photo de profil de <?= $_SESSION["user"]->firstName ?> <?= $_SESSION["user"]->lastName ?>">
                                    </a>
                                    <ul class="dropdown-menu mt-2" aria-labelledby="dropdownNavbarUser">
                                        <li><a class="dropdown-item" href="<?php echo WEBROOT ?>/users"><i class="fas fa-user"></i> <?= $_SESSION["user"]->firstName ?> <?= $_SESSION["user"]->lastName ?></a></li>
                                        <li><a class="dropdown-item" href="<?php echo WEBROOT ?>/users"><i class="fas fa-cogs"></i> Tableau de bord</a></li>
                                        <li><a class="dropdown-item" href="<?php echo WEBROOT ?>/users/logout"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a></li>
                                    </ul>
                                </div>
                            <?php
                            } else {
                                echo '<a class="btn btn-primary mb-2" href="' . WEBROOT . '/users/login">Se connecter</a>';
                            } ?>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
    </header>

    <div class="mt-2 mb-2" id="min-page-height">

        <?php echo $content_for_layout; ?>

    </div>

    <footer class="row bg-secondary text-white py-3" id="footer">
        <div class="col-md mt-auto text-center mb-auto">
            <a href="/">
                <img src="<?php echo WEBROOT ?>/webroot/img/logo.png" style="width: 200px;" alt="F1 News">
            </a>
        </div>
        <div class="col-md my-auto text-center">
            <ul class="list-unstyled">
                <li><a href="<?php echo WEBROOT ?>/news" class="link-light text-decoration-none">Actualités</a></li>
                <li><a href="<?php echo WEBROOT ?>/browse/calendar" class="link-light text-decoration-none">Calendrier</a></li>
            </ul>
        </div>
        <div class="col-md my-auto text-center">
            <ul class="list-unstyled">
                <li><a href="<?php echo WEBROOT ?>/pages/show/4" class="link-light text-decoration-none">Mentions légales</a></li>
                <li><a href="<?php echo WEBROOT ?>/pages/show/5" class="link-light text-decoration-none">CGU</a></li>
            </ul>
        </div>
        <div class="col-md my-auto text-center me-3">
            Toute l'actualité de la Formule 1 !<br>
            Tous droits réservés <b>&copy; F1 News</b>
        </div>
    </footer>

    <script src="<?php echo WEBROOT ?>/webroot/js/tools/min-page-height.js"></script>

    <script>
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl, {
                delay: 5000,
                animation: true
            })
        })

        for (var toast of toastList) {
            toast.show()
        }
    </script>

</body>

</html>