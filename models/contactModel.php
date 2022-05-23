<?php
class contactModel extends model
{
    var $table = "contact";

    function getCountForPrivilegeAndFilter($privilege, $filter)
    {
        $condition = " 1 = 1 ";
        $values = array();

        if ($privilege == "requests") {
            $condition .= " AND idUserSent = :idUserSent ";
            $values["idUserSent"] = $_SESSION["user"]->id;
        }

        if ($filter != 0) {
            $condition .= " AND actualState = :actualState ";
            $values["actualState"] = $filter;
        }

        return $this->findFirst(array(
            "fields" => "COUNT(*) AS count",
            "where" => "$condition",
            "values" => $values,
        ))->count;
    }

    function findAllForPrivilegeAndFilter($page, $step, $privilege, $filter)
    {
        $condition = " 1 = 1 ";
        $values = array();

        if ($privilege == "requests") {
            $condition .= " AND idUserSent = :idUserSent ";
            $values["idUserSent"] = $_SESSION["user"]->id;
        }

        if ($filter != 0) {
            $condition .= " AND actualState = :actualState ";
            $values["actualState"] = $filter;
        }

        return $this->find(array(
            "fields" => "u.*, contact.id AS idContact, contact.subject, contact.message, contact.dateSent, s.id AS idState, s.stateName, s.stateBadgeColor",
            "join" => "INNER JOIN users u ON u.id = contact.idUserSent INNER JOIN states s ON s.id = contact.actualState",
            "order" => 'contact.dateSent DESC',
            "limit" => "LIMIT " . ($step * $page - $step) . ", " . $step,
            "where" => "$condition",
            "values" => $values
        ));
    }

    function get($id)
    {
        return $this->findFirst(array(
            "fields" => "u.*, contact.id AS idContact, contact.subject, contact.idUserSent, contact.actualState, contact.message, contact.dateSent, s.id AS idState, s.stateName, s.stateBadgeColor, p.filename",
            "join" => "INNER JOIN users u ON u.id = contact.idUserSent INNER JOIN photos p ON u.profilePic = p.id INNER JOIN states s ON s.id = contact.actualState",
            "where" => "contact.id = :id",
            "values" => array("id" => $id)
        ));
    }


    function getContactRequestCountForUser($idUser)
    {
        return $this->findFirst(array(
            "fields" => "COUNT(*) AS count",
            "where" => " idUserSent = :idUserSent",
            "values" => array("idUserSent" => $idUser),
        ))->count;
    }
}
