<?php

class Bootstrap {
    function __construct() {
        $request = Request::getInstance();
        $controller = $request->getController();
        $file = __DIR__ . '/../controllers/' . $controller . '.php';
        if (file_exists($file)) {
            require $file;
            $c = new $controller;
        } else {
            require __DIR__ . '/../controllers/error.php';
            $c = new Error();
        }
        $c->run($request->getAction());

    }
}

?>