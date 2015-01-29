<?php

class Bootstrap {
    function __construct() {
        Session::init();
        require_once FILE . 'module/app/controllers/check.php';
        $request = Request::getInstance();
        $controller = $request->getController();
        $module = $request->getModule();
        $check = new Check;
        $hasUser=$check->index();
        $this->dispatch($hasUser,$controller);
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
    private function dispatch($hasUser,$controller){
        if($hasUser=='not'&&($controller.''!='login')&&($controller.''!='check')){
            header("Location:http://schedule.com/src/app/login");
            exit;
        }
        if($controller.''==='check'){
            echo $hasUser.''?$hasUser.'':'0';
            exit;
        }
        if($hasUser=="ok"&&($controller.''=='login')){
            header("Location:http://schedule.com/src/app/calendar");
        }
    }
}

?>
