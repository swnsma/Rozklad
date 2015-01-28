<?php

class Groups extends Controller {
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('groups');
    }

    public function index() {
        $data['title'] = 'title';
        $this->view->renderHtml('common/head', $data);
        $this->view->renderHtml('common/header');
        $this->view->renderHtml('groups/index', $data);
        $this->view->renderHtml('common/footer');
        $this->view->renderHtml('common/foot');
    }

    public function listGroup() {
        $this->view->renderJson($this->model->getList());
    }

}