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
        $this->userModel = $this->loadModel('user');
    }

    public function index() {
        $data = [];
        $data['database']=URL.'SQL/data/index.php?sqlite=&username=&db=rozklad.sqlite';
        $this->view->renderHtml('common/head');
        $this->view->renderHtml('admin/admin_header', $data);
        $this->view->renderHtml('admin/admin_page', $data);
        $this->view->renderHtml('common/footer');
        $this->view->renderHtml('common/foot');
    }

    public function getUnconfirmedUsers(){
        $unconfirmedUsers=$this->model->getUnconfirmedUsers();
        $this->view->renderJson($unconfirmedUsers);
    }

    public function getTeachers(){
        $teachers=$this->model->getTeachers();
        $this->view->renderJson($teachers);
    }

    public function confirmUser(){
        $req=Request::getInstance();
        $id = $req->getParam(0);
        $name =$this->model->confirmUser($id);
        //Відправляємо листа вчитeлю

        if(is_null($name['key'])||!$name['key']){
            $m = Mail::getInstance();
            $template = $m->getTemplate('letterToTeacher2', array(
                'userName' => $name['name'].' '.$name['surname'],
                'mail_background' => 'mail_background',
                'url' => URL.'app/calendar',
                'date'=> date("d.m.Y H:i")
            ));
            if (is_null($template)) {
                echo 'template is not exists';
            } else {
                $m->addFileToHtml(DOC_ROOT . 'public/img/mail_background2.jpg', 'mail_background');
                if ($m->send(array( $name['email']), 'subject', $template));
            }
        }
        //
    }

    public function unConfirmUser(){
        $req=Request::getInstance();
        $id = $req->getParam(0);
        $this->model->unConfirmUser($id);
    }
}
