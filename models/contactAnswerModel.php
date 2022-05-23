<?php
class contactAnswerModel extends model
{
    var $table = "contact_answer";

    function findAllForContactRequest($idContactBelong)
    {
        return $this->find(array(
            "fields" => "u.*, contact_answer.id AS idContactAnswer, contact_answer.message, contact_answer.dateSent, contact_answer.idUserSent, p.filename",
            "join" => "INNER JOIN users u ON u.id = contact_answer.idUserSent INNER JOIN photos p ON u.profilePic = p.id",
            "where" => "idContactBelong = :idContactBelong",
            "values" => array("idContactBelong" => $idContactBelong)
        ));
    }
}
