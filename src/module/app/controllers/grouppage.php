<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 1/28/2015
 * Time: 3:53 PM
 */
class GroupPage extends Controller {
    public static $role='student';
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('grouppage');
        $id=1;
    }

    public function index() {
        $data = 'hi'; //викликаємо портрібні функції поделі
        $this->view->renderHtml('grouppage/index', $data);
    }
    public function delUser(){
        $req = Request::getInstance();
        $id= $req->getParam(0);
        $this->model->delUser($id);
        $this->view->renderJson(Array('result'=>"success"));
    }
    public function renameGroup(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        $newName= $req->getParam(1);
        $this->model->renameGroup($id, $newName);
        $this->view->renderJson(Array('result'=>"success"));

    }
    public  function createInviteCode(){
        $req= Request::getInstance();
        $id=$req->getParam(0);
        $this->model->createInviteCode($id);
        $this->view->renderJson(Array('code'=>$this->model->getInviteCode()));
    }
    public function editDescription(){
        $req= Request::getInstance();
        $id=$req->getParam(0);
        $newDescription = $req->getParam(1);
        $this->model->editDescription($id, $newDescription);
        $this->view->renderJson(Array('result'=>"success"));
    }
    public function sendSchedule(){
        $req= Request::getInstance();
        $id=$req->getParam(0);
        $var=$this->model->loadSchedule($id);
        $this->view->renderJson($var);
    }
    public function sendUsers(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        $var=$this->model->getUsers();
        if(!isset($var)){
        $var=$this->model->loadUsers($id);
        }
        $this->view->renderJson($var);
    }
    public function sendGroupInfo(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        $var=$this->model->getGroupInfo();
        if(!isset($var)){
            $var=$this->model->loadData($id);
        }
        $this->view->renderJson($var);
    }
    public function sendCode(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        $var=$this->model->getInviteCode($id);
        if(!isset($var)){
            $var=$this->model->loadCode($id);
        }
        $this->view->renderJson(Array($var));
    }

}
