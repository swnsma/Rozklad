<?php

class Index extends Controller {
    function __construct() {
        parent::__construct();
    }

    function index() {
        $model = $this->loadModel('index');

        $data = 'hi';

        $this->view->renderHtml('index/index', $data);
    }
}

?>