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
        $this->model->loadData($id);
    }

    public function index() {
        $data = 'hi'; //викликаємо портрібні функції поделі
        $this->view->renderHtml('grouppage/index', $data);
    }
    public function delUser(){
        $req = Request::getInstance();
        $id= $req->getParam(1);
        $this->model->delUser($id);
        $this->view->renderJson("success");
    }
    public function renameGroup(){
        $req=Request::getInstance();
        $newName= $req->getParam(1);
        $this->model->renameGroup($newName);
        $this->view->renderJson("success");

    }
    public  function createInviteCode(){
        $this->model->createInviteCode();
        print ($this->model->getInviteCode());
        $this->view->renderJson($this->model->getInviteCode());
    }
    public function editDescription(){
        $req= Request::getInstance();
        $newDescription = $req->getParam(1);
        $this->model->editDescription($newDescription);
        $this->view->renderJson("success");
    }

}