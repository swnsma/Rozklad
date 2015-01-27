<?php
require_once FILE . 'core/MagicObject.php';
//require_once FILE . 'inc/facebook.php'; //include fb sdk
class Bootstrap {
    function __construct() {
        $request = Request::getInstance();
        $controller = $request->getController();
        $module = $request->getModule();
        $file = FILE . 'controllers/' . $module . '/' . $controller . '.php';
        if (file_exists($file)) {
            require_once $file;
            $c = new $controller;
        } else {
            require_once FILE . 'controllers/app/error.php';
            $c = new Error();
        }
        $c->run($request->getAction());

//
    }
}

?>