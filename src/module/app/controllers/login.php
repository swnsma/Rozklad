<?php

class Login extends Controller {
    public function __construct() {
        parent::__construct();
        echo "hi";
        $this->model = $this->loadModel('login');
    }

    public function index() {
        $data = 'hi'; //викликаємо портрібні функції поделі
        $this->view->renderHtml('signin/index', $data);
    }
}

?>