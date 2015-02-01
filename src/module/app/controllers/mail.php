<?php

class Mail extends Controller {
    function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('mail');
    }

    function index() {
        $this->model->send();
        $this->view->renderAllHtml('mail/index');
    }
}