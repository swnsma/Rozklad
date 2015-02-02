<?php

class Groups extends Controller {
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('groups');
        $this->user_model = $this->loadModel('user');
        $this->user_info =$this->user_model->getInfo($_SESSION['idFB'])[0];
    }

    public function index() {
        $data['title'] = 'Group list';
        $data['status'] = 1; //$this->user_info['role_id'];
        $data['groups'] = $this->model->getList();
        $this->view->renderAllHTML('groups/index',
            $data,
            array('groups/groups.css'));
    }

    public function create() {
        $data['title'] = 'Create Group';
        $status = 1; //$$this->user_info['role_id'];

        if ($status == 1) {
            $data['teacher_name'] = $this->user_info['name'] . ' ' . $this->user_info['surname'];
            $this->view->renderAllHTML('groups/creategroup',
                $data,
                array('groups/create_group.css'));
        } else {
            $this->view->renderHtml('error/access');
        }
    }

    public function createNewGroup() {
        if (isset($_POST['name']) && isset($_POST['descr'])) {
            $name = $_POST['name'];
            $descr = $_POST['descr'];
            if (preg_match('/^[\d+\w+]{1,50}$/', $name)
                && preg_match('/^[\(\)\!\?\:\;\.\, \d+\w+]{1,300}$/m', $descr)) {
                $status = 1; //$this->user_info['role_id'];
                if ($status == 1) {
                    $data = $this->model->createGroup($this->user_info['id'], $name, $descr);
                    if ($data == null) {
                        $this->view->renderJson(array(
                            'status' => 'create_error'
                        ));
                    } else {
                        $this->view->renderJson(array(
                            'status' => 'group_create',
                            'key' => $data['key'],
                            'id' => $data['id']
                        ));
                    }
                } else {
                    $this->view->renderJson(array(
                        'status' => 'denied_access'
                    ));
                }
            } else {
                $this->view->renderJson(array(
                    'status' => 'invalid_data'
                ));
            }
        } else {
            $this->view->renderJson(array(
                'status' => 'no_data'
            ));
        }
    }
}