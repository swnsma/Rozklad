<?php

class Bootstrap {
    function __construct() {

        Session::init();
        require_once FILE . 'module/app/controllers/check.php';
        $request = Request::getInstance();
        $controller = $request->getController();
        $action=$request->getAction();
        $module = $request->getModule();


        if(!isset($_SESSION["idFB"])||$controller=='login') {
//            echo $_SESSION["idFB"];
            $hasUser="ok";
            $check = new Check;
            $hasUser = $check->check();
            $this->dispatch($hasUser,$controller,$action);
        }

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
    private function dispatch($hasUser,$controller,$action){
        require_once FILE .'module/app/controllers/login.php';
        if($controller=="login"){
            Login::$status=$hasUser;
        }
        if(($hasUser=='not'||$hasUser=='regist')&&($controller.''!='login')&&($controller.''!='check')){
            header("Location:http://custom.l/src/app/login");
            exit;
        }
        if(($controller.''==='check')&&($action=="check")){
            echo $hasUser.''?$hasUser.'':'0';
            exit;
        }
        if($hasUser=="ok"&&($controller.''=='login')){
            header("Location:http://custom.l/src/app/calendar");
        }
    }
}
?>
