<?php
require_once DOCUMENT_ROOT . 'core/magic_object.php';

abstract class Controller  extends MagicObject{
    function __construct() {
        $this->view = new View();
    }

    public function loadModel($name) {
        $path = DOCUMENT_ROOT . 'models/'. Request::getInstance()->getModule() . '/'. $name . '_model.php';
        if (file_exists($path)) {
            require_once $path;
            $modelName = ucfirst($name) . 'Model';
            return new $modelName;
        }
        return null;
    }

    public function run($actionName = 'index') {
        $this->$actionName();
    }
}

?>