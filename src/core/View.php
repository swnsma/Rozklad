<?php

class View {
    function __construct() {}

    public function render($name, $data) {
        $path =  __DIR__ . '/../views/' . Request::getInstance()->getModule() . '/'. $name . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
}

?>