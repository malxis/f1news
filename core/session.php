<?php
class Session
{
    function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    /**
     * Add a flash message to be show at the next page loading
     *
     * @param string $message Message to show
     * @param string $icon
     * @param string $type success, warning, danger... (Bootstrap default colors)
     * @return void
     */
    public function setFlash($message, $icon = "", $type = "success")
    {
        if (!isset($_SESSION["flash"]))
            $_SESSION["flash"] = array();

        array_push($_SESSION["flash"], array(
            'message' => $message,
            'type' => $type,
            'icon' => $icon
        ));
    }

    /**
     * Show the flash messages in a view
     *
     * @return void
     */
    public function flash()
    {
        if (!empty($_SESSION["flash"])) {
            echo "<div class='row text-center d-flex justify-content-center'>";
            echo "<div class='col-6'>";
            foreach ($_SESSION["flash"] as $key => $value) {
                $html = '<div class="alert alert-' . $value["type"] . ' alert-dismissible d-inline-block m-3" role="alert"><div class="d-flex justify-content-center">';
                $html .= $value["icon"];
                $html .= '<div>';
                $html .= $value["message"];
                $html .= '</div></div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button></div>';
                $_SESSION["flash"][$key] = array();
                echo $html;
                unset($_SESSION["flash"][$key]);
            }
            echo "</div>";
            echo "</div>";
        }
    }

    /**
     * Write values in the session
     *
     * @param string $key Key where the value will be written
     * @param mixed $value Value to write
     * @return void
     */
    public function write($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function read($key = null)
    {
        if ($key) {
            if (isset($_SESSION["key"])) {
                return $_SESSION["key"];
            } else {
                return false;
            }
        } else {
            return $_SESSION;
        }
    }

    public function isLogged()
    {
        return isset($_SESSION["user"]->id);
    }

    public function isAdmin()
    {
        return ($this->isLogged() && $_SESSION["user"]->idAdmin != null);
    }

    public function user($key)
    {
        if ($this->read("user")) {
            if (isset($this->read("user")->$key)) {
                return $this->read("user")->$key;
            }
        }
        return false;
    }
}
