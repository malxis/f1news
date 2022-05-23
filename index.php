<?php

define('ROOT', str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]));
// echo "ROOT : " . ROOT . "<br>";
// example : C:/wamp64/www/2nd_year/d3_demars/framework/

define('WEBROOT', substr(str_replace("public_html", "", strstr(ROOT, "public_html")), 0, -1));
// echo "WEBROOT : " . WEBROOT . "<br>";
// example : /2nd_year/d3_demars/framework

define('WEBROOT_WITH_DOMAIN', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . WEBROOT);
// echo "WEBROOT_WITH_DOMAIN : " . WEBROOT_WITH_DOMAIN . "<br>";
// example : http://localhost/2nd_year/d3_demars/framework

date_default_timezone_set('Europe/Paris');

require(ROOT . "config/conf.php");
require(ROOT . "core/model.php");
require(ROOT . "core/controller.php");
require(ROOT . "core/session.php");
require(ROOT . "core/tools.php");

$path = "";
if (strlen(WEBROOT) >= 1) {
    $path = explode("/", str_replace(WEBROOT . "/", "", str_replace("index.php", "", $_SERVER["REQUEST_URI"])));
} else {
    $path = explode("/", str_replace("index.php", "", $_SERVER["REQUEST_URI"]));
    unset($path[0]);
    $path = array_values($path);
}
// print_r($path);

if (empty($path[0])) {
    $controller = "homeController";
} else {
    $controller = $path[0] . "Controller";
}

//echo $controller;

if (empty($path[1])) {
    $action = "index";
} else {
    $action = $path[1];
}

// Instantiate the controller
if (file_exists(ROOT . "controllers/" . $controller . ".php")) {
    require(ROOT . "controllers/" . $controller . ".php");
    $controller = new $controller();
} else {
    require_once(ROOT . "controllers/homeController.php");
    $controller = new homeController();
    $action = "error";
    $path[2] = "404";
}

// Delete the params who are now useless in $path (controller and action)
unset($path[0]);
unset($path[1]);
// Reset the indexes
$path = array_values($path);

// We check if the action (function) exists in the controller
if (method_exists($controller, $action)) {
    call_user_func_array(array($controller, $action), $path);
} else {
    require_once(ROOT . "controllers/homeController.php");
    $controller = new homeController();
    $path[0] = "404";
    call_user_func_array(array($controller, "error"), $path);
}
