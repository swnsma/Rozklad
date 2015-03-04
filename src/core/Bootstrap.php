<?php

class Bootstrap extends Controller
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        Session::init(3600 * 24, 'MYSES');
        new Base_Install();
        $request = Request::getInstance();
        $url = $request->getUrl();
        if(preg_match('/grouppage/', $url)||preg_match('/groups/', $url)){
            Session::set('unusedLink',$url);
        }
        $controller = $request->getController();
        $action=$request->getAction();
        $module = $request->getModule();

        $this->dispatcher($controller);

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

    private function initSes($time,$ses)
    {
        Session::init($time,$ses);
    }

    private function dispatcher($controller)
    {
        $this->setLogoutLink();
        $this->checkLogout($controller);
        $this->checkUnconf();
        $this->checkStatus();
        $this->checkId($controller);
        $this->checkRoute($controller);
    }

    private function checkController($controller)
    {
        return
            $controller==='calendar'||
            $controller==='grouppage'||
            $controller==='groups'||
            $controller==='lesson';
    }

    private function checkStatus()
    {
        if(!Session::has('status')) {
            Session::set("status",'not');
        }
    }

    private function checkLogout($controller)
    {
        if($controller=='logout') {
            $this->logout();
        }
    }

    private function setLogoutLink()
    {
        if(!Session::has("logout_link")) {
            Session::set('logout_link',URL."app/logout");
        }
    }

    private function checkUnconf() {
        if(Session::has('status') && Session::get('status')!="not" && (Session::has('id'))) {
            $this->model=$this->loadModel('user');

            if(!$this->model->checkUnconfirmed(Session::get('id'))) {
                Session::set('status','ok');
            } else{
                Session::set('status','unconfirmed');
            }
        }
    }

    private function changeLocation($location = '') {
        header("Location:".URL.$location);
        exit;
    }

    private function checkId($controller) {
        if((Session::get('status')!='not') && (Session::get('status')!='regist')&&$this->checkController($controller)) {
            if (Session::has('id')) {
                $userInfo = $this->model->getCurrentUserInfo(Session::get('id'));
                if ($userInfo === null) {
                    $this->logout();
                }
            } else {
                $this->logout();
            }
        }
    }

    private function checkRoute($controller)
    {
        if(Session::has('status')){
            $status = Session::get('status');
            switch($status){
                case 'not':
                    if($this->checkController($controller)&& $controller != 'index') {
                        $this->changeLocation();
                    }
                    break;
                case 'regist':
                    if($controller!='regist'&&$controller!='sendermail') {
                        $this->changeLocation("app/regist");
                    }
                    break;
                case 'unconfirmed':
                    if($controller!="index"&&$controller!="logout"&&$controller!='sendermail') {
                        $this->changeLocation();
                    }
                    break;
                case 'ok':
                    if($controller=='index'||$controller=='regist') {
                        $this->changeLocation("app/calendar");
                    }
                    break;
            }
        } else {
            if($controller!="index" && $this->checkController($controller)) {
                header("Location:" . URL);
                exit;
            }
        }
    }
}