<?php

class Bootstrap extends Controller{
    private $model;
    function __construct() {
        parent::__construct();
        $this->initSes(3600*24, 'MYSES');
        $this->model=$this->loadModel('user');
        require_once DOC_ROOT . 'module/app/controllers/regist.php';
        $request = Request::getInstance();
        $urla=$request->getUrl();
        if(preg_match('/grouppage/', $urla)||preg_match('/groups/', $urla)){
            Session::set('unusedLink',$urla);
        }
        $controller = $request->getController();
        $action=$request->getAction();
        $module = $request->getModule();

        $this->dispatcher($controller,$action);

        $file = DOC_ROOT  . 'module/' . $module . '/controllers/' . $controller . '.php';
        if (file_exists($file)) {
            require_once $file;
            $c = new $controller;
        } else {
            require_once  DOC_ROOT . 'module/app/controllers/error.php';
            $c = new Error();
        }
        $c->run($request->getAction());
    }

    private function initSes($time,$ses){
        Session::init($time,$ses);
    }
    private  function dispatcher($controller,$action){
        $this->setLogoutLink();
        $this->checkLogout($controller);
        $this->checkUnconf();
        $this->checkStatus();
        $this->checkId($controller);
        $this->checkRoute($controller,$action);
    }
    private function checkController($controller){
        return
            $controller=='calendar'||
            $controller=='grouppage'||
//            $controller=='admin'||
            $controller=='groups';
    }
    private  function checkStatus(){
        if(!Session::has('status')){
            Session::set("status",'not');
        }
    }
    private function checkLogout($controller){
        if($controller=='logout'){
            $this->logout_link();
        }
    }
    private function setLogoutLink(){
        if(!Session::has("logout_link")){
            Session::set('logout_link',URL."app/logout");
        }
    }
    private function logout_link(){
        $this->logout();
        header("Location:".URL);
    }
    private  function checkUnconf(){
        if(Session::has('status')&&Session::get('status')!="not"&&(Session::has('id'))){
            $this->model=$this->loadModel('user');

            if(!$this->model->checkUnconfirmed(Session::get('id'))){
                Session::set('status','ok');
            }
            else{
                Session::set('status','unconfirmed');
            }
        }
    }
    private  function changeLocation($location = ''){
        header("Location:".URL.$location);
        exit;
    }
    private function checkId($controller){
        if((Session::get('status')!='not')&&(Session::get('status')!='regist')&&$this->checkController($controller)) {
            if (Session::has('id')){
                $userInfo = $this->model->getCurrentUserInfo(Session::get('id'));
                if ($userInfo === null) {
                    $this->logout();
                }
            }
            else {
                $this->logout();

            }
        }
    }
    private function checkRoute($controller,$action){
        if(Session::has('status')){
            $status = Session::get('status');
            switch($status){
                case 'not':
                    if($this->checkController($controller)&& $controller != 'index')
                    {
                        $this->changeLocation();
                    }
                    break;
                case 'regist':
                    if($controller!='regist'){
                        $this->changeLocation("app/regist");
                    }
                    break;
                case 'unconfirmed':
                    if($controller!="index"&&$controller!="logout"){
                        $this->changeLocation();
                    }
                    break;
                case 'ok':
                    if($controller=='index'||$controller=='regist'){
                        $this->changeLocation("app/calendar");
                    }
                    break;
            }
        }
        else{
            if($controller!="index" && $this->checkController($controller)){
                header("Location:" . URL);
                exit;
            }
        }
    }
}