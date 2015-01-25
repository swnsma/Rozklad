<?php

class Bootstrap {
    function __construct() {
        /*if (Session::get('loggedIn') == false) {
            Session::destroy();
            header('Location: ' . URL . '/app/login');
        }*/
        $request = Request::getInstance();
        $controller = $request->getController();
        $module = $request->getModule();
        $file = DOCUMENT_ROOT . 'controllers/' . $module . '/' . $controller . '.php';
        if (file_exists($file)) {
            require_once $file;
            $c = new $controller;
        } else {
            require_once DOCUMENT_ROOT . 'controllers/app/error.php';
            $c = new Error();
        }
        $c->run($request->getAction());

    }
}

?>