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
//        if(!Session::has('unusedLink')&&!preg_match('/signin/', $urla)&&Session::get('status')!='ok'){
//            Session::set('unusedLink',$urla);
//        }
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
            $controller=='admin'||
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
        Session::set("status","not");
        Session::uns('id');
        header("Location:".URL."app/signin");
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
    private  function changeLocation($location){
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
                    if($this->checkController($controller)&&($controller!='signin'))
                    {
                        $this->changeLocation("app/signin");
                    }
                    break;
                case 'regist':
                    if($controller!='regist'){
                        $this->changeLocation("app/regist");
                    }
                    break;
                case 'unconfirmed':
                    if($controller!="signin"&&$controller!="logout"){
                        $this->changeLocation("app/signin");
                    }
                    break;
                case 'ok':
                    if($controller=='signin'||$controller=='regist'){
                        $this->changeLocation("app/calendar");
                    }
                    break;
            }
        }
        else{
            if($controller!="signin"&&$this->checkController($controller)){
                header("Location:".URL."app/signin");
                exit;
            }
        }
    }
}