<?php

class newsController extends controller
{
    var $models = array('newsModel', 'flashNewsModel', 'commentsModel');

    function index()
    {
        $this->show();
    }

    function show($page = "1")
    {
        // Pagination
        $d["path"] = WEBROOT . "/news/show/";
        $d["totalElementCount"] = $this->newsModel->getCount();
        $d["elementPerPage"] = 5;
        if ($page < 1 || ($d["totalElementCount"] != 0 && $page > ceil($d["totalElementCount"] / $d["elementPerPage"]))) {
            return $this->redirect("/news");
        }
        $d["page"] = $page;

        $d["flashNewsList"] = $this->flashNewsModel->findAll(1, 5);
        $d["newsList"] = $this->newsModel->findAll($d["page"], $d["elementPerPage"]);

        $d["title"] = "Actualités - F1 News";

        $this->set($d);
        $this->renderWithFlashNews("list");
    }

    function read($id, $page = "1")
    {
        // Add a comment
        if (!empty($_POST)) {
            if (!empty($_POST["content"])) {

                if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

                    // Recaptcha verification
                    $secret = '6Lc5RNoaAAAAAK8yF-M8OP4RWOmzgmAoFqYqmwpL';
                    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
                    $responseData = json_decode($verifyResponse);

                    if ($responseData->success) {
                        unset($_POST['g-recaptcha-response']);

                        $_POST["content"] = htmlentities($_POST["content"]);
                        $_POST["datePosted"] = date('Y-m-d H:i:s');
                        $_POST["idNewsBelong"] = $id;
                        $_POST["idUserCommented"] = $_SESSION["user"]->id;
                        if ($this->commentsModel->save($_POST)) {
                            $this->session->setFlash("Votre commentaire a bien été envoyé. Un administrateur doit vérifier votre commentaire avant publication.", '<i class="fas fa-check me-2"></i>', "success");
                            return $this->redirect("/news/read/" . $id . "/" . $page);
                        } else {
                            $this->session->setFlash("Une erreur est survenue lors de l'ajout de votre commentaire.", '<i class="fas fa-times me-2"></i>', "danger");
                        }
                    } else {
                        $d["invalidCaptcha"] = true;
                        $this->session->setFlash("Veuillez valider le captcha.", '<i class="fas fa-times me-2"></i>', "danger");
                    }
                } else {
                    $d["invalidCaptcha"] = true;
                    $this->session->setFlash("Veuillez valider le captcha.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            } else {
                $this->session->setFlash("Votre commentaire ne peut pas être vide.", '<i class="fas fa-times me-2"></i>', "danger");
            }
        }

        // Pagination for comments
        $d["path"] = WEBROOT . "/news/read/" . $id . "/";
        $d["totalElementCount"] = $this->commentsModel->getCountForState($id, "approved");
        $d["elementPerPage"] = 10;
        if ($page < 1 || ($d["totalElementCount"] != 0 && $page > ceil($d["totalElementCount"] / $d["elementPerPage"]))) {
            return $this->redirect("/news/read/" . $id);
        }
        $d["page"] = $page;

        $d["news"] = $this->newsModel->get($id);
        $d["comments"] = $this->commentsModel->findAllAtPage($id, $page, $d["elementPerPage"], "approved");
        $d["flashNewsList"] = $this->flashNewsModel->findAll(1, 5);

        $d["title"] = $d["news"]->title . " - F1 News";

        $this->set($d);
        $this->renderWithFlashNews("read");
    }

    function admin($page = "1")
    {
        if ($this->session->isAdmin()) {
            // Pagination
            $d["path"] = WEBROOT . "/news/admin/";
            $d["totalElementCount"] = $this->newsModel->getCount();
            $d["elementPerPage"] = 10;
            if ($page < 1 || ($d["totalElementCount"] != 0 && $page > ceil($d["totalElementCount"] / $d["elementPerPage"]))) {
                return $this->redirect("/news/admin");
            }
            $d["page"] = $page;

            $d["newsList"] = $this->newsModel->findAll($page, $d["elementPerPage"]);

            foreach ($d["newsList"] as $news) {
                $news->nbApproved = $this->commentsModel->getCountForState($news->idNews, "approved");
                $news->nbWaiting = $this->commentsModel->getCountForState($news->idNews, "waiting");
                $news->nbRefused = $this->commentsModel->getCountForState($news->idNews, "refused");
            }

            $d["title"] = "Administration des actualités - F1 News";

            $this->set($d);
            $this->renderWithPanel("admin_list");
        } else {
            $this->redirect();
        }
    }

    function manage($id = null)
    {
        if ($this->session->isAdmin()) {
            $d["news"] = null;

            if (!empty($_POST)) {
                if (!empty($_POST["content"])) {
                    $redirect = false;

                    $_POST["content"] = htmlentities($_POST["content"]);
                    if ($id == null)
                        $_POST["datePosted"] = date('Y-m-d H:i:s');
                    $_POST["idUserPosted"] = $_SESSION["user"]->id;

                    if ($this->newsModel->save($_POST)) {
                        $redirect = true;
                        if ($id != null) {
                            $this->session->setFlash("L'article a bien été édité.", '<i class="fas fa-check me-2"></i>', "success");
                        } else {
                            $this->session->setFlash("L'article a bien été créée.", '<i class="fas fa-check me-2"></i>', "success");
                        }
                    } else {
                        $this->session->setFlash("Une erreur est survenue lors de la sauvegarde de l'article.", '<i class="fas fa-times me-2"></i>', "danger");
                    }
                    $id = $this->newsModel->id;

                    if (!empty($_FILES["mainPicture"]["size"])) {
                        $oldImage = $this->newsModel->getImage($id, "mainPicture");

                        if ($this->saveImage($id, "mainPicture")) {
                            $redirect = true;
                            if ($oldImage) {
                                $this->deleteImage($oldImage->id);
                            }
                        } else {
                            $redirect = false;
                        }
                    }

                    if ($redirect) {
                        return $this->redirect("/news/admin");
                    }
                } else {
                    $this->session->setFlash("Le contenu de l'article ne peut pas être vide.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            }

            $d["title"] = "Ajouter un article - F1 News";
            if ($id != null) {
                $d["news"] = $this->newsModel->get($id);
                $d["title"] = "Éditer un article - F1 News";
            }
            $this->set($d);
            $this->renderWithPanel("admin_form");
        } else {
            $this->redirect();
        }
    }

    function delete($id = null)
    {
        if ($this->session->isAdmin()) {
            if ($id != null) {
                $idPhoto = $this->newsModel->getImage($id, "mainPicture")->id;
                if ($this->newsModel->delete($id)) {
                    if ($this->deleteImage($idPhoto)) {
                        $this->session->setFlash("L'article a bien été supprimé.", '<i class="fas fa-check me-2"></i>', "success");
                    } else {
                        $this->session->setFlash("Une erreur est survenue lors de la suppression de l'article.", '<i class="fas fa-times me-2"></i>', "danger");
                    }
                } else {
                    $this->session->setFlash("Une erreur est survenue lors de la suppression de l'article. Veuillez vérifier que l'article ne contient aucun commentaire avant de le supprimer.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            } else if (!empty($_POST["listid"])) {
                $noError = true;
                foreach ($_POST["listid"] as $idnews) {
                    $idPhoto = $this->newsModel->getImage($idnews, "mainPicture")->id;
                    if (!$this->newsModel->delete($idnews)) {
                        $noError = false;
                    }
                    if (!$this->deleteImage($idPhoto)) {
                        $noError = false;
                    }
                }

                if ($noError) {
                    $this->session->setFlash("Les articles ont bien été supprimées.", '<i class="fas fa-check me-2"></i>', "success");
                } else {
                    $this->session->setFlash("Une erreur est survenue lors de la suppression d'un ou plusieurs article(s).", '<i class="fas fa-times me-2"></i>', "danger");
                }
            } else {
                $this->session->setFlash("Veuillez sélectionner un ou plusieurs article(s) à supprimer.", '<i class="fas fa-times me-2"></i>', "danger");
            }

            $this->redirect("/news/admin");
        } else {
            $this->redirect();
        }
    }
}
