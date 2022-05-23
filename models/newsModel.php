<?php
class newsModel extends model
{
    var $table = "news";

    function findAll($page, $elementPerPage)
    {
        return $this->find(array(
            "fields" => "u.*, news.id AS idNews, news.content, news.datePosted, news.title, p.filename, pn.filename as newsPicture",
            "join" => "INNER JOIN users u ON u.id = news.idUserPosted INNER JOIN photos p ON u.profilePic = p.id INNER JOIN photos pn ON news.mainPicture = pn.id",
            "order" => 'news.datePosted DESC',
            "limit" => "LIMIT " . ($elementPerPage * $page - $elementPerPage) . ", " . $elementPerPage
        ));
    }

    function get($id)
    {
        return $this->findFirst(array(
            "fields" => "u.*, news.id AS idNews, news.content, news.datePosted, news.title, p.filename, pn.filename as newsPicture",
            "join" => "INNER JOIN users u ON u.id = news.idUserPosted INNER JOIN photos p ON u.profilePic = p.id INNER JOIN photos pn ON news.mainPicture = pn.id",
            "where" => "news.id = :id",
            "values" => array("id" => $id)
        ));
    }

    function getNewsPostedCountForUser($idUser)
    {
        return $this->findFirst(array(
            "fields" => "COUNT(*) AS count",
            "where" => " idUserPosted = :idUserPosted",
            "values" => array("idUserPosted" => $idUser),
        ))->count;
    }
}
