<?php

class Bootstrap {
    function __construct() {
        $request = Request::getInstance();
        $controller = $request->getController();
        $file = __DIR__ . '/../controllers/' . $controller . '.php';
        if (file_exists($file)) {
            require $file;
            new $controller;
        } else {
            require __DIR__ . '/../controllers/error.php';
            new Error();
        }

    }
}

?>