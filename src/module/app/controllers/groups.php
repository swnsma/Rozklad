<?php

require_once DOC_ROOT . 'core/UploadImage.php';

class Groups extends Controller {
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('groups');
        $this->user_model = $this->loadModel('user');
        $this->user_info = $this->user_model->getCurrentUserInfo(Session::get('id'));
    }

    public function index() {
        $data['title'] = 'Группы';
        $data['groups'] = $this->model->getList();
        $data['name'] = $this->user_info['name'] . ' ' . $this->user_info['surname'];
        $data['status'] = $this->user_info['title'];
        $data['status'] = $this->user_info['title'];
        $data['photo']='http://graph.facebook.com/'. $this->user_info['fb_id'] . '/picture?type=large';
        /*$this->view->renderAllHTML('groups/index',
            $data,
            array('groups/groups.css'));*/
        $this->view->renderHtml('common/head');
        $this->view->renderHtml('common/header', $data);
        $this->view->renderHtml('groups/index', $data);
        $this->view->renderHtml('common/footer');
        $this->view->renderHtml('common/foot');
    }
    public function getGroupList(){
        $var = $this->model->getList();
        if(isset($var)){
            $this->view->renderJson($var);
        }
    }
    public function create() {
        $data['title'] = 'Создать группу';
        if ($this->user_info['title'] == 'teacher') { // ==
            $data['teacher_name'] = $this->user_info['name'] . ' ' . $this->user_info['surname'];
            $data['name'] = $this->user_info['name'] . ' ' . $this->user_info['surname'];
            $data['status'] = $this->user_info['title'];
            $data['status'] = 'teacher';
            $data['photo']='http://graph.facebook.com/'. $this->user_info['fb_id'] . '/picture?type=large';
            /*$this->view->renderAllHTML('groups/creategroup',
                $data,
                array('groups/create_group.css'));*/

            $this->view->renderHtml('common/head');
            $this->view->renderHtml('common/header', $data);
            $this->view->renderHtml('groups/creategroup', $data);
            $this->view->renderHtml('common/footer');
            $this->view->renderHtml('common/foot');

        } else {
            $this->view->renderHtml('error/access');
        }
    }

    public function createNewGroup() {

        if (isset($_POST['name']) && isset($_POST['descr'])) {
            $name = $_POST['name'];
            $descr = $_POST['descr'];

            if (count($name)&&count($descr)) {
                $status = 1;
//            $this->user_info['role_id'];

                if ($status == 1) {
                    $image = null;

                    if (isset($_FILES['photo']['error']) || !is_array($_FILES['photo']['error'])) {
                        $upload = new UploadImage($_FILES['photo']);
                        if ($upload->checkFileError() && $upload->upload()) {
                            $image = $upload->getUploadFileName();
                        } else {
                            $this->view->renderJson(array(
                                'status' => $upload->getError()
                            ));
                            return;
                        }
                    }
                    $data = $this->model->createGroup($this->user_info['id'], $name, $descr, $image);
                    //$data = $this->model->createGroup(1, $name, $descr, $image);
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