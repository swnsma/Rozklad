<?php

class Bootstrap {
    function __construct() {
        $time = 3600*24;
        $ses = 'MYSES';
        Session::init($time,$ses);
        $_SESSION['status']="not";

        require_once FILE . 'module/app/controllers/regist.php';
        $request = Request::getInstance();
        $controller = $request->getController();
        $action=$request->getAction();
        $module = $request->getModule();
        $this->checkRoute($controller,$action);
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
    protected function checkRoute($controller,$action){
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

        if($_SESSION['status']&&($_SESSION['status']=="regist")&&$controller!="regist"&&$action!="back_signin"){
            if($_SESSION['has_email']===1){
//                header("Location:http://vk.com");
                exit;
            }
            else{
                header("Location:".URL."app/regist");
                exit;
            }
        }
    }

}
?>
