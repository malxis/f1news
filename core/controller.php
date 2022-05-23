<?php

class controller
{

    var $vars = array();
    var $layout = "default";

    function __construct()
    {
        // Load all the models
        if (isset($this->models)) {
            foreach ($this->models as $m) {
                $this->loadModel($m);
            }
        }
        $this->session = new Session();
        $this->tools = new tools();
    }

    /**
     * Load and return a view
     *
     * @param string $filename
     * @return string
     */
    function loadView($filename)
    {
        extract($this->vars);
        $className = str_replace("Controller", "", get_class($this));

        ob_start();
        require(ROOT . 'views/' . $className . "/" . $filename . '.php');
        return ob_get_clean();
    }

    /**
     * Default function to render a view
     *
     * @param string $filename file to render
     * @return void
     */
    function render($filename)
    {
        $content_for_layout = $this->loadView($filename);

        // Load the view in the choosen layout
        if ($this->layout == false) {
            echo $content_for_layout;
        } else {
            require(ROOT . 'views/layout/' . $this->layout . '.php');
        }
    }

    function redirect($path = "/")
    {
        header("Location: " . WEBROOT_WITH_DOMAIN . $path);
    }

    /**
     * Set the data in the array that will be send to the view
     *
     * @param array $d
     * @return void
     */
    function set($d)
    {
        $this->vars = array_merge($this->vars, $d);
    }

    /**
     * Load the view and render it with the panel aside (on the right)
     *
     * @param string $filename
     * @return void
     */
    function renderWithPanel($filename)
    {
        $d["content_with_panel"] = $this->loadView($filename);

        $this->set($d);
        $this->render("../layout/panel");
    }


    /**
     * Load the view and render it with the flashnews aside (on the left)
     *
     * @param string $filename
     * @return void
     */
    function renderWithFlashNews($filename)
    {
        $d["content_with_flashnews"] = $this->loadView($filename);

        $this->set($d);
        $this->render("../layout/flashnews");
    }

    /**
     * Save an image
     *
     * @param int $id id of the row in the table of the model where the photo will be saved
     * @param string $field field where the photo will be stored
     * @param string $newName new name of the file
     * @return bool
     */
    function saveImage($id, $field, $newName = null)
    {
        $extension = array("jpeg", "jpg", "png");
        $className = str_replace("Controller", "", get_class($this));
        $model = $className . "Model";

        $file_name = $_FILES[$field]["name"];
        $file_tmp = $_FILES[$field]["tmp_name"];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);

        if (!empty($newName))
            $file_name = $newName . "." . $ext;

        if (in_array($ext, $extension)) {
            if (!file_exists(ROOT . "webroot/img/" . $className . "/" . $file_name)) {
                move_uploaded_file($file_tmp, ROOT . "webroot/img/" . $className . "/" . $file_name);
                if (!$this->$model->saveImage($id, $field, $file_name)) {
                    $this->session->setFlash("Une erreur est survenue lors de l'enregistrement de l'image " . $file_name . " dans la base de données.", '<i class="fas fa-times me-2"></i>', "danger");
                    unlink(ROOT . "webroot/img/" . $className . "/" . $file_name);
                    return false;
                }
            } else {
                $this->session->setFlash("Une image avec le nom " . $file_name . " existe déjà.", '<i class="fas fa-times me-2"></i>', "danger");
                return false;
            }
        } else {
            $this->session->setFlash("L'image " . $file_name . " n'est pas au bon format.", '<i class="fas fa-times me-2"></i>', "danger");
            return false;
        }

        return true;
    }

    /**
     * Delete the photo identified by $idPhoto
     *
     * @param int $idPhoto
     * @return bool
     */
    function deleteImage($idPhoto)
    {
        $className = str_replace("Controller", "", get_class($this));

        // First we delete the file on the server
        $this->loadModel("photosModel");
        $image = $this->photosModel->findFirst(array("where" => "id = :id", "values" => array("id" => $idPhoto)));
        unlink(ROOT . "webroot/img/" . $className . "/" . $image->filename);

        // After we delete the photo in the database
        return $this->photosModel->deleteImage($idPhoto);
    }

    function loadModel($name)
    {
        require_once "models/" . $name . ".php";
        $this->$name = new $name();
    }
}
