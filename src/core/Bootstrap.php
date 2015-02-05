<?php

class Bootstrap {
    function __construct() {
        $time = 3600*24;
        $ses = 'MYSES';
        Session::init($time,$ses);
        require_once DOC_ROOT . 'module/app/controllers/regist.php';
        $request = Request::getInstance();
        $urla=$request->getUrl();
        if(!Session::has('unusedLink')){
            Session::set('unusedLink',$urla);
        }
        require_once DOC_ROOT . 'module/app/controllers/regist.php';
        $controller = $request->getController();
        $action=$request->getAction();
        $module = $request->getModule();

        $this->checkStatus();
        $this->checkRoute($controller,$action);

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

    protected function checkController($controller){
        return
            $controller=='calendar'||
            $controller=='grouppage'||
            $controller=='admin';
    }

    protected  function checkStatus(){
        if(!Session::has('status')){
            Session::set("status",'not');
        }
    }
    protected function checkRoute($controller,$action){
        if(Session::has('status')){
            if($_SESSION['status']==='not')
            {
                if($this->checkController($controller))
                {
                    header("Location:".URL."app/signin");
                    exit;
                }
            }
//        if(((isset($_SESSION['fb_ID'])&&$_SESSION['fb_ID']&&!(empty($_SESSION['fb_ID'])))||
//                (isset($_SESSION['gm_ID'])&&$_SESSION['gm_ID']&&!(empty($_SESSION['gm_ID']))))&&
//            $controller==='signin'
//        )
//        {
//            header("Location:".URL."app/calendar");
//            exit;
//        }
            if((Session::get('status')==='unconfirmed')&&($controller!=='signin'))
            {
                header("Location:".URL."app/signin");
                exit;
            }

            if((Session::get('status')=="regist")&&($controller!="regist")){
                    header("Location:".URL."app/regist");
                    exit;
            }
            if(Session::get('status')==='ok'&&$controller=='signin'){
                header("Location:".URL."app/calendar");
                exit;
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
?>