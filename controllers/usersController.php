<?php

class usersController extends controller
{
    var $models = array('usersModel', 'contactModel', 'commentsModel', 'newsModel');

    function index()
    {
        if ($this->session->isLogged()) {
            $d = array();
            if ($this->session->isAdmin()) {
                $d["newsPostedCount"] = $this->newsModel->getNewsPostedCountForUser($_SESSION["user"]->id);
            }
            $d["commentsPostedCount"] = $this->commentsModel->getCommentsPostedCountForUser($_SESSION["user"]->id);
            $d["contactRequestCount"] = $this->contactModel->getContactRequestCountForUser($_SESSION["user"]->id);
            $d["title"] = $_SESSION["user"]->firstName  . " " . $_SESSION["user"]->lastName . " - F1 News";
            $this->set($d);
            $this->renderWithPanel("profile");
        } else {
            $this->redirect();
        }
    }

    function password()
    {
        if ($this->session->isLogged()) {

            if (!empty($_POST)) {
                extract($_POST);
                $data["id"] = $_SESSION["user"]->id;
                $data["password"] = md5($new_password);
                if ($this->usersModel->save($data)) {
                    $this->session->setFlash("Votre mot de passe à bien été mis à jour.", '<i class="fas fa-check me-2"></i>', "success");
                    return $this->redirect("/users");
                } else {
                    $this->session->setFlash("Une erreur est survenue lors de la mise à jour de votre mot de passe.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            }

            $d["title"] = "Modifier mon mot de passe - F1 News";
            $this->set($d);
            $this->renderWithPanel("form_edit_password");
        } else {
            $this->redirect();
        }
    }

    function data()
    {
        if ($this->session->isLogged()) {

            if (!empty($_POST)) {

                $imageUploaded = true;
                if (!empty($_FILES["profilePic"]["size"])) {

                    $oldImage = $this->usersModel->getImage($_SESSION["user"]->id, "profilePic");

                    if ($this->saveImage($_SESSION["user"]->id, "profilePic", $_SESSION["user"]->id . "_profilePicture")) {
                        if ($oldImage->filename != "default.png") {
                            $this->deleteImage($oldImage->id);
                        }
                    } else {
                        $imageUploaded = false;
                    }
                }

                $data["id"] = $_POST["id"];
                $data["email"] = $_POST["email"];
                $data["firstName"] = $_POST["firstName"];
                $data["birthDate"] = $_POST["birthDate"];
                $data["lastName"] = $_POST["lastName"];
                $data["tel"] = $_POST["tel"];
                if ($this->usersModel->save($data) && $imageUploaded) {
                    $this->session->setFlash("Vos informations personnelles ont bien été mises à jour.", '<i class="fas fa-check me-2"></i>', "success");
                    $this->session->write('user', $this->usersModel->getUser($data["email"]));
                    return $this->redirect("/users");
                } else {
                    $this->session->setFlash("Une erreur est survenue lors de la mise à jour de vos informations personnelles.", '<i class="fas fa-times me-2"></i>', "danger");
                }
            }

            $d["title"] = "Modifier mes informations - F1 News";
            $this->set($d);
            $this->renderWithPanel("form_edit_data");
        } else {
            $this->redirect();
        }
    }

    function login()
    {
        if (!empty($_POST)) {
            extract($_POST);
            $user = $this->usersModel->getUser($email);

            if (!empty($user) && $user->password == md5($password)) {
                $this->session->setFlash("Bonjour et bienvenue " . $user->firstName . " " . $user->lastName . " !", '<i class="fas fa-check me-2"></i>', "success");
                $this->session->write('user', $user);
                $this->usersModel->updateLastLogin($_SESSION["user"]->id);
            } else {
                $this->session->setFlash("Identifiants incorrects, veuillez réessayer.", '<i class="fas fa-times me-2"></i>', "danger");
            }
        }

        if ($this->session->isLogged()) {
            $this->redirect();
        } else {
            $d["title"] = "Connexion - F1 News";
            $this->set($d);
            $this->render('login');
        }
    }

    function logout()
    {
        unset($_SESSION["user"]);
        $this->redirect();
    }

    function register()
    {
        $d["title"] = "Inscription - F1 News";

        if (!empty($_POST)) {

            if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

                // Recaptcha verification
                $secret = '6Lc5RNoaAAAAAK8yF-M8OP4RWOmzgmAoFqYqmwpL';
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
                $responseData = json_decode($verifyResponse);

                if ($responseData->success) {
                    unset($_POST['g-recaptcha-response']);
                    $_POST["password"] = md5($_POST["password"]);
                    $_POST["registerDate"] = date('Y-m-d H:i:s');
                    if ($this->usersModel->save($_POST)) {
                        $this->session->write('user', $this->usersModel->getUser($_POST["email"]));
                        $this->session->setFlash("Inscription réussie ! Bienvenue sur F1 News.", '<i class="fas fa-check me-2"></i>', "success");
                        $this->usersModel->updateLastLogin($_SESSION["user"]->id);
                    }
                } else {
                    $d["invalidCaptcha"] = true;
                    $this->set($d);
                    return $this->render('register');
                }
            } else {
                $d["invalidCaptcha"] = true;
                $this->set($d);
                return $this->render('register');
            }
        }

        if ($this->session->isLogged()) {
            $this->redirect();
        } else {
            $this->set($d);
            $this->render('register');
        }
    }

    function is_email_free()
    {
        echo $this->usersModel->isEmailFree(json_decode(file_get_contents("php://input"))->email);
    }

    function is_password_correct()
    {
        $data = json_decode(file_get_contents("php://input"));
        echo $this->usersModel->isPasswordCorrect($data->userId, $data->password);
        // echo $data->userId . " : " . $data->password;
    }
}
