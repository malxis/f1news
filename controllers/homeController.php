<?php

class homeController extends controller
{

    function index()
    {
        $d["title"] = "Accueil - F1 News";
        $this->set($d);
        $this->render("index");
    }

    function error($codeNumber)
    {
        $d["error_code"] = $codeNumber;
        $d["title"] = "Erreur $codeNumber - F1 News";

        $this->set($d);
        $this->render("error");
    }
}
