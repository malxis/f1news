<?php

class contactController extends controller
{
    var $models = array('contactModel', 'contactAnswerModel', 'statesModel');

    function index()
    {
        if ($this->session->isLogged()) {
            if (!empty($_POST)) {
                $_POST["dateSent"] = date('Y-m-d H:i:s');
                $_POST["idUserSent"] = $_SESSION["user"]->id;
                if ($this->contactModel->save($_POST)) {
                    $this->session->setFlash("Votre message a bien été envoyé.", '<i class="fas fa-check me-2"></i>', "success");
                    return $this->redirect();
                } else {
                    $this->session->setFlash("Une erreur est survenue lors de l'envoi de votre message.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            }

            $d["title"] = "Contactez-nous - F1 News";
            $this->set($d);
            $this->render("contact-form");
        } else {
            $this->session->setFlash("Vous devez être connecté pour nous contacter.", '<i class="fas fa-times me-2"></i>', "danger");
            $this->redirect("/users/login");
        }
    }

    function requests($page = "1")
    {
        if ($this->session->isLogged()) {
            return $this->list("requests", $page);
        } else {
            return $this->redirect();
        }
    }

    function admin($page = "1")
    {
        if ($this->session->isAdmin()) {
            return $this->list("admin", $page);
        } else {
            return $this->redirect();
        }
    }

    function list($privilege, $page = "1")
    {
        $filter = 0;
        if (isset($_POST["filter"])) {
            $filter = $_POST["filter"];
            $_SESSION["contact_filter"] = $filter;
        } else if (isset($_SESSION["contact_filter"]) && $privilege == "admin") {
            $filter = $_SESSION["contact_filter"];
        }


        // Pagination
        $d["path"] = WEBROOT . "/contact/$privilege/";
        $d["totalElementCount"] = $this->contactModel->getCountForPrivilegeAndFilter($privilege, $filter);
        $d["elementPerPage"] = 10;
        if ($page < 1 || ($d["totalElementCount"] != 0 && $page > ceil($d["totalElementCount"] / $d["elementPerPage"]))) {
            return $this->redirect("/contact/$privilege");
        }
        $d["page"] = $page;

        $d["privilege"] = $privilege;
        $d["filter"] = $filter;
        $d["statesList"] = $this->statesModel->find();
        $d["contactRequestsList"] = $this->contactModel->findAllForPrivilegeAndFilter($page, $d["elementPerPage"], $privilege, $filter);

        if ($privilege == "admin") {
            $d["title"] = "Demandes - F1 News";
        } else {
            $d["title"] = "Mes demandes - F1 News";
        }

        // If $_POST is set, we redirect to avoid the "confirm form resubmission" window
        if (isset($_POST["filter"])) {
            $this->redirect("/contact/$privilege/$page");
        } else {
            $this->set($d);
            $this->renderWithPanel("list");
        }
    }

    function see($privilege, $id = null)
    {
        if ($id != null) {

            // User send a answer to the request
            if (isset($_POST["message"])) {
                $_POST["idContactBelong"] = $id;
                $_POST["dateSent"] = date("Y-m-d H:i:s");
                $_POST["idUserSent"] = $_SESSION["user"]->id;
                if ($this->contactAnswerModel->save($_POST)) {
                    return $this->redirect("/contact/see/$privilege/$id");
                } else {
                    $this->session->setFlash("Une erreur est survenue lors de l'envoi du message, veuillez contacter l'administrateur du site.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            }

            // User want to change the state of the request
            if (isset($_POST["actualState"])) {
                if ($this->contactModel->save($_POST)) {
                    $this->session->setFlash("Le statut de la demande a été changé.", '<i class="fas fa-check me-2"></i>', "success");
                    return $this->redirect("/contact/see/$privilege/$id");
                } else {
                    $this->session->setFlash("Une erreur est survenue lors du changement du statut de la demande, veuillez contacter l'administrateur du site.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            }

            $d["privilege"] = $privilege;
            $d["contactData"] = $this->contactModel->get($id);
            $d["contactAnswerData"] = $this->contactAnswerModel->findAllForContactRequest($id);

            // We store the side of the contact message according to the user id
            // If the user open it's own contact request, we put on the right else on the left
            if ($d["contactData"]->idUserSent == $_SESSION["user"]->id) {
                if ($privilege == "admin") {
                    $this->session->setFlash("Vous ne pouvez pas répondre à votre propre demande en tant qu'administrateur.", '<i class="fas fa-times me-2"></i>', "danger");
                    return $this->redirect("/contact/" . $privilege);
                } else
                    $d["userSeeHisOwnRequest"] = true;
            } else
                $d["userSeeHisOwnRequest"] = false;

            $d["title"] = $d["contactData"]->subject . " - F1 News";

            $this->set($d);
            $this->renderWithPanel("see");
        } else {
            $this->redirect();
        }
    }
}
