<?php
class usersModel extends model
{
    var $table = "users";

    function getUser($email)
    {
        return $this->findFirst(array(
            "fields" => "users.*, admin.id AS idAdmin, filename",
            "join" => "LEFT JOIN admin ON users.id = admin.id INNER JOIN photos p ON users.profilePic = p.id",
            "where" => ' email = :email ',
            "values" => array("email" => $email)
        ));
    }

    function isEmailFree($email)
    {
        return $this->findFirst(array(
            "fields" => "COUNT(*) AS nbUser",
            "where" => ' email = :email',
            "values" => array("email" => $email)
        ))->nbUser == 0 ? 1 : 0;
    }

    function isPasswordCorrect($id, $password)
    {
        return $this->findFirst(array(
            "fields" => "COUNT(*) AS nbUser",
            "where" => ' id = :id AND password = :password ',
            "values" => array("id" => $id, "password" => $password)
        ))->nbUser;
    }

    function updateLastLogin($idUser)
    {
        $data["id"] = $idUser;
        $data["lastLogIn"] = date("Y-m-d H:i:s");
        $this->save($data);
    }
}
