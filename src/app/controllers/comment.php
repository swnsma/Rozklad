<?php


class Comment extends Controller
{
    private $lessonId;
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model=$this->loadModel("comment");
    }

    public function index()
    {
        $this->view->renderHtml("comment/index");
    }

    public function tree()
    {
        $comments=$this->model->index( Request::getPost('id'));
        $this->view->renderJson($comments);
    }

    public function addComment()
    {
        $resp=$this->model->addComment(Request::getPost('data'));
        $this->view->renderJson($resp);
    }

    public function removeComment()
    {
        $resp=$this->model->removeComment(Request::getPost('id'));
        if($resp){
            $this->view->renderJson("ok");
            exit;
        }
        $this->view->renderJson("not");
        exit;
    }

    public function getCurrentUser()
    {
        $this->model = $this->loadModel('user');
        $userInfo=$this->model->getCurrentUserInfo(Session::get('id'));
        $this->view->renderJson($userInfo);
    }
}
