<?php

class flashnewsController extends controller
{
    var $models = array('flashNewsModel');

    function admin($page = "1")
    {
        if ($this->session->isAdmin()) {
            // Pagination
            $d["path"] = WEBROOT . "/flashnews/admin/";
            $d["totalElementCount"] = $this->flashNewsModel->getCount();
            $d["elementPerPage"] = 10;
            if ($page < 1 || ($d["totalElementCount"] != 0 && $page > ceil($d["totalElementCount"] / $d["elementPerPage"]))) {
                return $this->redirect("/flashnews/admin");
            }
            $d["page"] = $page;

            $d["flashNewsList"] = $this->flashNewsModel->findAll($page, $d["elementPerPage"]);

            $d["title"] = "Administration des flash infos - F1 News";

            $this->set($d);
            $this->renderWithPanel("admin");
        } else {
            $this->redirect();
        }
    }

    function manage($id = null)
    {
        if ($this->session->isAdmin()) {
            $d["flashNews"] = null;

            if (!empty($_POST)) {
                if (!empty($_POST["content"])) {
                    $_POST["content"] = htmlentities($_POST["content"]);
                    if ($id == null)
                        $_POST["datePosted"] = date('Y-m-d H:i:s');
                    $_POST["idUserPosted"] = $_SESSION["user"]->id;
                    if ($this->flashNewsModel->save($_POST)) {
                        if ($id != null) {
                            $this->session->setFlash("La flash info a bien été éditée.", '<i class="fas fa-check me-2"></i>', "success");
                        } else {
                            $this->session->setFlash("La flash info a bien été créée.", '<i class="fas fa-check me-2"></i>', "success");
                        }
                    } else {
                        $this->session->setFlash("Une erreur est survenue lors de la sauvegarde de la flash info.", '<i class="fas fa-times me-2"></i>', "danger");
                    }
                    $id = $this->flashNewsModel->id;
                } else {
                    $this->session->setFlash("Le contenu de la flash info ne peut pas être vide.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            }

            $d["title"] = "Ajouter une flash info - F1 News";
            if ($id != null) {
                $d["title"] = "Éditer une flash info - F1 News";
                $d["flashNews"] = $this->flashNewsModel->get($id);
            }
            $this->set($d);
            $this->renderWithPanel("form");
        } else {
            $this->redirect();
        }
    }

    function delete($id = null)
    {
        if ($this->session->isAdmin()) {
            if ($id != null) {
                if ($this->flashNewsModel->delete($id)) {
                    $this->session->setFlash("La flash info a bien été supprimée.", '<i class="fas fa-check me-2"></i>', "success");
                } else {
                    $this->session->setFlash("Une erreur est survenue lors de la suppression de la flash info.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            } else if (!empty($_POST["listid"])) {
                $noError = true;
                foreach ($_POST["listid"] as $idFlashNews) {
                    if (!$this->flashNewsModel->delete($idFlashNews)) {
                        $noError = false;
                    }
                }

                if ($noError) {
                    $this->session->setFlash("Les flash infos ont bien été supprimées.", '<i class="fas fa-check me-2"></i>', "success");
                } else {
                    $this->session->setFlash("Une erreur est survenue lors de la suppression d'une ou plusieurs flash info(s).", '<i class="fas fa-times me-2"></i>', "danger");
                }
            } else {
                $this->session->setFlash("Veuillez sélectionner une ou plusieurs flash info(s) à supprimer.", '<i class="fas fa-times me-2"></i>', "danger");
            }

            $this->redirect("/flashnews/admin");
        } else {
            $this->redirect();
        }
    }
}
