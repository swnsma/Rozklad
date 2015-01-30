<?php

class Groups extends Controller {
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('groups');
        $this->user_model = $this->loadModel('user');

    }

    public function index() {
        $data['title'] = 'Group list';
        $user_info =$this->user_model->getInfo($_SESSION['idFB'])[0];
        $data['photo']="http://graph.facebook.com/".$user_info['fb_id']."/picture?type=large";
        $data['status'] = 1; //$user_info['role_id']; //$user_info[0]['role_id'];
        $data['name'] = 'name';

        $data['groups'] = $this->model->getList();
        $this->view->renderHtml('common/head');
        $this->view->renderHtml('common/header', $data);
        $this->view->renderHtml('groups/index', $data);
        $this->view->renderHtml('common/footer');
        $this->view->renderHtml('common/foot');
        /*$this->view->renderAllHTML('groups/index',
            array('common/bootstrap.css',
                'groups/groups.css'), $data);*/
    }
}