<?php


class Login extends Controller {
    function __construct() {
        parent::__construct();
    }

    public function index() {
        $model = $this->loadModel('login');

        $data = 'hi'; //викликаємо портрібні функції поделі

        $this->view->renderHtml('login/index', $data);
    }

    public function check_user() {
        //$this->view->renderJson();
    }
}

?>