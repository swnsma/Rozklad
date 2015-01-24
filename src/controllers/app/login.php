<?php


class Login extends Controller {
    function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = $this->loadModel('login');
        $this->view->render('login/index', $data);
    }
}

?>