<?php

abstract class Controller {
    public function __construct() {
        $this->view = new View();
    }

    public function loadModel($name) {
        $path = FILE . 'module/' . Request::getInstance()->getModule() . '/model/' . $name . '_model.php';
        if (file_exists($path)) {
            require_once $path;
            $modelName = ucfirst($name) . 'Model';
            return new $modelName;//::getInstance();
        }
        return null;
    }

    public function run($actionName = 'index') {
        $this->$actionName();
    }
}

?>