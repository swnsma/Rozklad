<?php

class Bootstrap {
    function __construct() {

        Session::init();
        require_once FILE . 'module/app/controllers/check.php';
        $request = Request::getInstance();
        $controller = $request->getController();
        $action=$request->getAction();
        $module = $request->getModule();
        $this->checkRoute($controller);
        $file = FILE  . 'module/' . $module . '/controllers/' . $controller . '.php';
        if (file_exists($file)) {
            require_once $file;
            $c = new $controller;
        } else {
            require_once  FILE . 'module/app/controllers/error.php';
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
    protected function checkRoute($controller){
        if((!(isset($_SESSION['fb_ID'])&&$_SESSION['fb_ID']&&!(empty($_SESSION['fb_ID'])))&&
                (!(isset($_SESSION['gm_ID'])&&$_SESSION['gm_ID']&&!(empty($_SESSION['gm_ID'])))))
            &&($this->checkController($controller))
        )
        {
            header("Location:".URL."app/signin");
            exit;
        }

        if(((isset($_SESSION['fb_ID'])&&$_SESSION['fb_ID']&&!(empty($_SESSION['fb_ID'])))||
                (isset($_SESSION['gm_ID'])&&$_SESSION['gm_ID']&&!(empty($_SESSION['gm_ID']))))&&
            $controller==='signin'
        )
        {
            header("Location:".URL."app/calendar");
            exit;
        }
    }

}
?>
