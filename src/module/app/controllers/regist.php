<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.01.2015
 * Time: 23:54
 */
class Regist extends Controller{
    public $model;
    public function __constructor(){
        parent::__construct();

    }
    public function index(){
        $request=Request::getInstance();
        $name = $request->getParam(0);
        $surname =$request->getParam(1);
        $phone =$request->getParam(2);
        $this->model=$this->loadModel("regist");
        $fb_id=$_SESSION['idFB'];
        echo $this->model->index($name,$surname,$phone,$fb_id);
    }

}