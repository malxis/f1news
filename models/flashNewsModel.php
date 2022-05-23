<?php
class flashNewsModel extends model
{
    var $table = "flash_news";

    function findAll($page, $step)
    {
        return $this->find(array(
            "fields" => "u.*, flash_news.id AS idFlashNews, flash_news.content, flash_news.datePosted",
            "join" => "INNER JOIN users u ON u.id = flash_news.idUserPosted",
            "order" => 'flash_news.datePosted DESC',
            "limit" => "LIMIT " . ($step * $page - $step) . ", " . $step
        ));
    }

    function get($id)
    {
        return $this->findFirst(array(
            "fields" => "u.*, flash_news.id AS idFlashNews, flash_news.content, flash_news.datePosted",
            "join" => "INNER JOIN users u ON u.id = flash_news.idUserPosted",
            "where" => "flash_news.id = :id",
            "values" => array("id" => $id)
        ));
    }
}
