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
        $id=$_POST['id'];
        $comments=$this->model->index($id);
        $this->view->renderJson($comments);
    }

    public function addComment()
    {
        $data=$_POST['data'];
        $resp=$this->model->addComment($data);
        $this->view->renderJson($resp);
    }

    public function removeComment()
    {
        $id=$_POST['id'];
        $resp=$this->model->removeComment($id);
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
