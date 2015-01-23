<?php

class Error extends Controller {
    function __construct() {
        parent::__construct();
        $data = $this->loadModel('login');
        $this->view->render('error/index', $data);
    }
}

?>