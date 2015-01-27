<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 27.01.2015
 * Time: 16:45
 */

class Calendar extends Controller {
    public static $role='teacher';
    public static $id=1;
    private $model;
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->model = $this->loadModel('admin');
        $data = 'hi';
        $this->view->renderHtml('admin/admin_page', $data);
    }

    public function getUnconfirmedUsers(){
        $this->model = $this->loadModel('admin');
        $unconfirmedUsers=$this->model->getUnconfirmedUsers();
        $this->view->renderJson($unconfirmedUsers);
    }
}
?>