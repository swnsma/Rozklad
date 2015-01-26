<?php
require_once DOCUMENT_ROOT . 'core/magic_object.php';
class View extends MagicObject {
    function __construct() {}

    public function renderHtml($name, $data = null) {
        $path = DOCUMENT_ROOT . 'views/' . Request::getInstance()->getModule() . '/'. $name . '.phtml';
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