<?php

class Groups extends Controller {
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('groups');
    }

    public function index() {
        $data = 'hi'; //викликаємо портрібні функції поделі
        $this->view->renderHtml('groups/index', $data);
    }

    public function listGroup() {
        $this->view->renderJson($this->model->getList());
    }

}