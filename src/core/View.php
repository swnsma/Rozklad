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

    function renderAllHTML($page, $data = null, $files = array()) {
        $this->renderHtml('common/head');
        $header_data['title'] = isset($data['title']) ? $data['title'] : 'default title';
        $header_data['files'] = $files;
        $header_data['name'] = 23;
        $this->renderHtml('common/header', $header_data);
        $this->renderHtml($page, $data);
        $this->renderHtml('common/footer');
        $this->renderHtml('common/foot');
    }
}

?>