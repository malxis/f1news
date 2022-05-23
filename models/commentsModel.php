<?php
class commentsModel extends model
{
    var $table = "comments";

    // Return the comments of a news at a specific page
    function findAllAtPage($idNews, $page, $elementPerPage, $state)
    {
        $select = "";
        $join = "";
        $condition = "";

        if ($state == "refused" || $state == "approved")
            $select = " , uu.id AS adminIdUser, uu.firstName AS adminFirstName, uu.lastName AS adminLastName ";

        if ($state == "refused" || $state == "approved")
            $join = " INNER JOIN users uu ON uu.id = comments.idUserControl ";

        if ($state == "approved")
            $condition = " AND approved = 1 ";
        else if ($state == "waiting")
            $condition = " AND approved = 0 AND idUserControl IS NULL ";
        else if ($state == "refused")
            $condition = " AND approved = 0 AND idUserControl IS NOT NULL ";

        return $this->find(array(
            "fields" => "comments.*, u.id AS idUser, u.firstName, u.lastName, p.filename $select",
            "join" => "INNER JOIN users u ON u.id = comments.idUserCommented INNER JOIN photos p ON u.profilePic = p.id $join",
            "where" => " comments.idNewsBelong = :idNewsBelong $condition",
            "values" => array("idNewsBelong" => $idNews),
            "limit" => "LIMIT " . ($elementPerPage * $page - $elementPerPage) . ", " . $elementPerPage
        ));
    }

    // Return all the comments of a news
    function findAll($idNews, $state)
    {
        $select = "";
        $join = "";
        $condition = "";

        if ($state == "refused" || $state == "approved")
            $select = " , uu.id AS adminIdUser, uu.firstName AS adminFirstName, uu.lastName AS adminLastName ";

        if ($state == "refused" || $state == "approved")
            $join = " INNER JOIN users uu ON uu.id = comments.idUserControl ";

        if ($state == "approved")
            $condition = " AND approved = 1 ";
        else if ($state == "waiting")
            $condition = " AND approved = 0 AND idUserControl IS NULL ";
        else if ($state == "refused")
            $condition = " AND approved = 0 AND idUserControl IS NOT NULL ";

        return $this->find(array(
            "fields" => "comments.*, u.id AS idUser, u.firstName, u.lastName, p.filename $select",
            "join" => "INNER JOIN users u ON u.id = comments.idUserCommented INNER JOIN photos p ON u.profilePic = p.id $join",
            "where" => " comments.idNewsBelong = :idNewsBelong $condition",
            "values" => array("idNewsBelong" => $idNews)
        ));
    }

    function getCountForState($idNews, $state)
    {
        $condition = "";

        if ($state == "approved") {
            $condition = " AND approved = 1 ";
        } else if ($state == "waiting") {
            $condition = " AND approved = 0 AND idUserControl IS NULL ";
        } else if ($state == "refused") {
            $condition = " AND approved = 0 AND idUserControl IS NOT NULL ";
        }

        return $this->findFirst(array(
            "fields" => "COUNT(*) AS count",
            "where" => " idNewsBelong = :idNewsBelong $condition",
            "values" => array("idNewsBelong" => $idNews),
        ))->count;
    }

    function getCommentsPostedCountForUser($idUser)
    {
        return $this->findFirst(array(
            "fields" => "COUNT(*) AS count",
            "where" => " idUserCommented = :idUserCommented",
            "values" => array("idUserCommented" => $idUser),
        ))->count;
    }

    function approveComment($idComment, $idUserControl)
    {
        $obj = array();
        $obj["id"] = $idComment;
        $obj["idUserControl"] = $idUserControl;
        $obj["approved"] = 1;
        $obj["reasonRefused"] = null;
        return $this->save($obj);
    }

    function refuseComment($idComment, $idUserControl, $reason)
    {
        $obj = array();
        $obj["id"] = $idComment;
        $obj["idUserControl"] = $idUserControl;
        $obj["approved"] = 0;
        $obj["reasonRefused"] = $reason;
        return $this->save($obj);
    }
}
