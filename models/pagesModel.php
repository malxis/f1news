<?php
class pagesModel extends model
{
    var $table = "pages";

    function get($id)
    {
        return $this->findFirst(array(
            "fields" => "u.*, pages.id AS idPage, pages.title, pages.content",
            "join" => "INNER JOIN users u ON u.id = pages.idUserPosted",
            "where" => "pages.id = :id",
            "values" => array("id" => $id)
        ));
    }

    function findAll($page, $elementPerPage)
    {
        return $this->find(array(
            "fields" => "u.*, pages.id AS idPage, pages.title, pages.content",
            "join" => "INNER JOIN users u ON u.id = pages.idUserPosted",
            "order" => 'pages.title',
            "limit" => "LIMIT " . ($elementPerPage * $page - $elementPerPage) . ", " . $elementPerPage
        ));
    }
}
