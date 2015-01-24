<?php

class Error extends Controller {
    function __construct() {
        parent::__construct();
    }

    function index() {
        $data = $this->loadModel('index');
        $this->view->render('error/index', $data);
    }
}

?>