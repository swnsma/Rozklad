<?php

class Index extends Controller {
    function __construct() {
        parent::__construct();
    }

    function index() {
        $model = $this->loadModel('index');
        if ($model !== null) {
            $model->example();
        }
        $data = 'hi';

        $this->view->renderHtml('index/index', $data);
    }
}

?>