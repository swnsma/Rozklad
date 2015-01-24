<?php

class View {
    function __construct() {}

    public function renderHtml($name, $data = null) {
        $path = DOCUMENT_ROOT . 'views/' . Request::getInstance()->getModule() . '/'. $name . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }

    public function renderJson($data) {
        print json_encode($data);
    }

}

?>