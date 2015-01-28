<?php

class Bootstrap {
    function __construct() {
        $request = Request::getInstance();
        $controller = $request->getController();
        $module = $request->getModule();
        $file = FILE  . 'module/' . $module . '/controllers/' . $controller . '.php';
        if (file_exists($file)) {
            require_once $file;
            $c = new $controller;
        } else {
            require_once  FILE . 'module/app/controllers/error.php';
            $c = new Error();
        }
        $c->run($request->getAction());

//
    }
}

?>