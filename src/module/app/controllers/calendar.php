<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 17:55
 * */

class Calendar extends Controller {
    public static $role='teacher';
    public static $id=1;
    private $model;
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->model = $this->loadModel('lesson');
        $data = 'hi';
        $this->view->renderHtml('calendar/index', $data);
    }

    public function addEvent(){
        $req=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $title= $req->getParam(0);
        $start= $req->getParam(1);
        $end= $req->getParam(2);
        $id=$this->model->addLesson($title,$start,$end);
        if($id==null){
            echo 'Ошибка';
        }else{
            $this->view->renderJson(array('id' => $id));
        }
    }

    public function addFullEvent(){
        $this->model = $this->loadModel('lesson');
        $start=Request::getInstance()->getParam(0);
        $end=Request::getInstance()->getParam(1);
        $id=$this->model->getAllEvent($start,$end);
        $this->view->renderJson($id);
    }
}
?>