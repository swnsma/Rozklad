<?php

class Index extends Controller {
    function __construct() {
        parent::__construct();
    }

    function index() {
        $data = $this->loadModel('index');
        $this->view->render('index/index', $data);
    }
}

?>