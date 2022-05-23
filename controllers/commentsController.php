<?php

class commentsController extends controller
{
    var $models = array('commentsModel');

    function manage($idNews)
    {
        if ($this->session->isAdmin()) {

            $d["commentsApproved"] = $this->commentsModel->findAll($idNews, "approved");
            $d["commentsWaiting"] = $this->commentsModel->findAll($idNews, "waiting");
            $d["commentsRefused"] = $this->commentsModel->findAll($idNews, "refused");

            $d["elementCountCommentsApproved"] = $this->commentsModel->getCountForState($idNews, "approved");
            $d["elementCountCommentsWaiting"] = $this->commentsModel->getCountForState($idNews, "waiting");
            $d["elementCountCommentsRefused"] = $this->commentsModel->getCountForState($idNews, "refused");

            $d["idNews"] = $idNews;
            $d["title"] = "Administration des commentaires - F1 News";

            $this->set($d);
            $this->renderWithPanel("admin_manage");
        } else
            $this->redirect();
    }

    // AJAX : approve a comment
    function approve_comment()
    {
        $data = json_decode(file_get_contents("php://input"));
        echo $this->commentsModel->approveComment($data->id, $_SESSION["user"]->id);
    }

    // AJAX : refuse a comment
    function refuse_comment()
    {
        $data = json_decode(file_get_contents("php://input"));
        echo $this->commentsModel->refuseComment($data->id, $_SESSION["user"]->id, urldecode($data->reason));
    }

    // AJAX : delete a comment
    function delete_comment()
    {
        $data = json_decode(file_get_contents("php://input"));
        echo $this->commentsModel->delete($data->id);
    }
}
