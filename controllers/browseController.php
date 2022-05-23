<?php

class browseController extends controller
{
    function index()
    {
        $this->calendar();
    }

    function calendar($year = null)
    {
        if (isset($_SESSION["calendar_year"]) && $year != $_SESSION["calendar_year"]) {
            $this->redirect("/browse/calendar/" . $_SESSION["calendar_year"]);
        }

        $d["title"] = "Calendrier - F1 News";
        $this->set($d);
        $this->render("calendar");
    }

    function races($year = null, $round = null)
    {
        if ($year && $round) {
            $d["title"] = "Course n°$round de $year - F1 News";
        } else {
            $d["title"] = "Course - F1 News";
        }
        $this->set($d);
        $this->render("races");
    }

    function circuits()
    {
        $d["title"] = "Circuit - F1 News";
        $this->set($d);
        $this->render("circuits");
    }

    function drivers()
    {
        $d["title"] = "Pilote - F1 News";
        $this->set($d);
        $this->render("drivers");
    }

    function constructors()
    {
        $d["title"] = "Constructeur - F1 News";
        $this->set($d);
        $this->render("constructors");
    }

    // AJAX : Set the year the user is looking at
    function set_year_looked_at()
    {
        $data = json_decode(file_get_contents("php://input"));
        $_SESSION["calendar_year"] = $data->year;
    }
}
