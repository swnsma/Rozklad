<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 12.02.2015
 * Time: 11:15
 */
class Lesson extends Controller {
    private $userInfo;

    public function __construct() {
        parent::__construct();
        $id = $_SESSION['id'];
        if($id===null){
            $this->logout();
        }
        $this->model = $this->loadModel('user');
        $this->userInfo=$this->model->getCurrentUserInfo($id);
        if($this->userInfo===null){
            $this->logout();
        }
    }
    public function getUserInfo(){
        $this->view->renderJson($this->userInfo);
    }
    public function index() {
        $req =Request::getInstance();
        $lessonId= $req->getParam(0);
        $this->model = $this->loadModel('lesson');
        $data['title'] = "Lesson|Rozklad";

        $data['groups'] = $this->model->getList();
        $data['name'] = $this->userInfo['name'] . ' ' . $this->userInfo['surname'];
        $data['status'] = $this->userInfo['title'];
        $data['photo']='http://graph.facebook.com/'. $this->userInfo['fb_id'] . '/picture?type=large';

        $this->view->renderHtml('common/head',$data);
        $this->view->renderHtml('common/header', $data);
        $this->view->renderHtml('lesson/index');

    }
}
