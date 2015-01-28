<?php

class View{
    public function __construct() {}

    public function renderHtml($name, $data = null) {
        $path = FILE . 'module/' . Request::getInstance()->getModule() . '/view/'. $name . '.phtml';
        if (file_exists($path)) {
            require_once $path;
        }
    }

    public function renderJson($data) {
        header('Content-Type: application/json');
        print json_encode($data);
    }
}

?>