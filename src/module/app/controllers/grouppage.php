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
        $this->model->delUser();
    }
    public function renameGroup($newName){
        $this->model->renameGroup($newName);
    }
    public  function createInviteCode(){
        $this->model->createInviteCode();
    }
    public function editDescription($newDescription){
        $this->model->editDescription($newDescription);
    }

}