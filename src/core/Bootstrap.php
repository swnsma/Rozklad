<?php

class Bootstrap {
    function __construct() {
        $request = Request::getInstance();
        $controller = $request->getController();
        $module = $request->getModule();
        $file = __DIR__ . '/../controllers/' . $module . '/' . $controller . '.php';
        if (file_exists($file)) {
            require $file;
            $c = new $controller;
        } else {
            require __DIR__ . '/../controllers/app/error.php';
            $c = new Error();
        }
        $c->run($request->getAction());

    }
}

?>