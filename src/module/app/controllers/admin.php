<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 27.01.2015
 * Time: 16:45
 */

class Admin extends Controller {
    public static $role='teacher';
    public static $id=1;
    private $model;
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('admin');
    }

    public function index() {
        $this->view->renderHtml('common/head');
        $this->view->renderHtml('common/header');
        $this->view->renderHtml('admin/admin_page');
        $this->view->renderHtml('common/footer');
        $this->view->renderHtml('common/foot');
    }

    public function getUnconfirmedUsers(){
        $unconfirmedUsers=$this->model->getUnconfirmedUsers();
        $this->view->renderJson($unconfirmedUsers);
    }

    public function confirmUser(){
        $req=Request::getInstance();
        $id = $req->getParam(0);
        $this->model->confirmUser($id);
    }

    public function unConfirmUser(){
        $req=Request::getInstance();
        $id = $req->getParam(0);
        $this->model->unConfirmUser($id);
    }
}
?>