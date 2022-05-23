<?php

class pagesController extends controller
{
    var $models = array('pagesModel');

    function index()
    {
        $this->redirect();
    }

    function show($id = null)
    {
        if ($id != null && is_numeric(($id))) {
            $d["pageData"] = $this->pagesModel->get($id);
            if ($d["pageData"]) {
                $d["title"] = $d["pageData"]->title . " - F1 News";
                $this->set($d);
                $this->render("show");
            } else {
                $this->redirect();
            }
        } else {
            $this->redirect();
        }
    }

    function admin($page = "1")
    {
        if ($this->session->isAdmin()) {
            // Pagination
            $d["path"] = WEBROOT . "/pages/admin/";
            $d["totalElementCount"] = $this->pagesModel->getCount();
            $d["elementPerPage"] = 10;
            if ($page < 1 || ($d["totalElementCount"] != 0 && $page > ceil($d["totalElementCount"] / $d["elementPerPage"]))) {
                return $this->redirect("/pages/admin");
            }
            $d["page"] = $page;

            $d["pagesList"] = $this->pagesModel->findAll($page, $d["elementPerPage"]);

            $d["title"] = "Administration des pages - F1 News";

            $this->set($d);
            $this->renderWithPanel("admin");
        } else {
            $this->redirect();
        }
    }

    function manage($id = null)
    {
        if ($this->session->isAdmin()) {
            $d["page"] = null;

            if (!empty($_POST)) {
                if (!empty($_POST["content"])) {
                    $_POST["content"] = htmlentities($_POST["content"]);
                    $_POST["idUserPosted"] = $_SESSION["user"]->id;
                    if ($this->pagesModel->save($_POST)) {
                        if ($id != null) {
                            $this->session->setFlash("La page a bien été éditée.", '<i class="fas fa-check me-2"></i>', "success");
                        } else {
                            $this->session->setFlash("La page a bien été créée.", '<i class="fas fa-check me-2"></i>', "success");
                        }
                    } else {
                        $this->session->setFlash("Une erreur est survenue lors de la sauvegarde de la page.", '<i class="fas fa-times me-2"></i>', "danger");
                    }
                    $id = $this->pagesModel->id;
                } else {
                    $this->session->setFlash("Le contenu de la page ne peut pas être vide.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            }

            $d["title"] = "Ajouter une page - F1 News";
            if ($id != null) {
                $d["title"] = "Éditer une page - F1 News";
                $d["page"] = $this->pagesModel->get($id);
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
                if ($this->pagesModel->delete($id)) {
                    $this->session->setFlash("La page a bien été supprimée.", '<i class="fas fa-check me-2"></i>', "success");
                } else {
                    $this->session->setFlash("Une erreur est survenue lors de la suppression de la page.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            } else if (!empty($_POST["listid"])) {
                $noError = true;
                foreach ($_POST["listid"] as $idPage) {
                    if (!$this->pagesModel->delete($idPage)) {
                        $noError = false;
                    }
                }

                if ($noError) {
                    $this->session->setFlash("Les pages ont bien été supprimées.", '<i class="fas fa-check me-2"></i>', "success");
                } else {
                    $this->session->setFlash("Une erreur est survenue lors de la suppression d'une ou plusieurs page(s).", '<i class="fas fa-times me-2"></i>', "danger");
                }
            } else {
                $this->session->setFlash("Veuillez sélectionner une ou plusieurs pages à supprimer.", '<i class="fas fa-times me-2"></i>', "danger");
            }

            $this->redirect("/pages/admin");
        } else {
            $this->redirect();
        }
    }
}
