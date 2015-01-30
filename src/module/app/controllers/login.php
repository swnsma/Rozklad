<?php

class Login extends Controller {
    public static $status;
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('login');

    }

    public function index() {
        $this->view->renderHtml('signin/index');
    }
}

?>