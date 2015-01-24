<?php

class View {
    function __construct() {}

    public function render($name, $data) {
        $path = DOCUMENT_ROOT . 'views/' . Request::getInstance()->getModule() . '/'. $name . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
}

?>