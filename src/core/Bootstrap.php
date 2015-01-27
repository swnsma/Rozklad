<?php
require_once DOCUMENT_ROOT . 'core/magic_object.php';
require_once DOCUMENT_ROOT . 'inc/facebook.php'; //include fb sdk
class Bootstrap extends  MagicObject{
    function __construct() {
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

//
    }
}

?>