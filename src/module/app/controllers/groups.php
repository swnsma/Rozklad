<?php

require_once DOC_ROOT . 'core/UploadImage.php';

class Groups extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = $this->loadModel('groups');
        $this->user_model = $this->loadModel('user');
        $this->user_info = $this->user_model->getCurrentUserInfo(Session::get('id'));
    }

    public function index()
    {
        $data['title'] = 'Группы';
        $data['groups'] = $this->model->getList();
        $data['name'] = $this->user_info['name'] . ' ' . $this->user_info['surname'];
        $data['status'] = $this->user_info['title'];
        $data['status'] = $this->user_info['title'];
        $data['photo']='http://graph.facebook.com/'. $this->user_info['fb_id'] . '/picture?type=large';
        $data['currentPage']=$this->getClassName();
        $this->view->renderHtml('common/head');
        $this->view->renderHtml('common/header', $data);
        $this->view->renderHtml('groups/index', $data);
        $this->view->renderHtml('common/footer');
        $this->view->renderHtml('common/foot');
    }

    public function getGroupList()
    {
        $var = $this->model->getList();
        if(isset($var)){
            $var[count($var)]=Session::get('id');
            $this->view->renderJson($var);
        }
    }

    public function create()
    {
        $data['title'] = 'Создать группу';
        if ($this->user_info['title'] == 'teacher') { // ==
            $data['teacher_name'] = $this->user_info['name'] . ' ' . $this->user_info['surname'];
            $data['name'] = $this->user_info['name'] . ' ' . $this->user_info['surname'];
            $data['status'] = $this->user_info['title'];
            $data['status'] = 'teacher';
            $data['photo']='http://graph.facebook.com/'. $this->user_info['fb_id'] . '/picture?type=large';
            $data['currentPage']=$this->getClassName();

            $this->view->renderHtml('common/head');
            $this->view->renderHtml('common/header', $data);
            $this->view->renderHtml('groups/creategroup', $data);
            $this->view->renderHtml('common/footer');
            $this->view->renderHtml('common/foot');
        } else {
            $this->view->renderHtml('error/access');
        }
    }

    public function archive()
    {
        $data['title'] = 'Архив групп';
        if ($this->user_info['title'] == 'teacher') {
            $data['teacher_name'] = $this->user_info['name'] . ' ' . $this->user_info['surname'];
            $data['name'] = $this->user_info['name'] . ' ' . $this->user_info['surname'];
            $data['status'] = $this->user_info['title'];
            $data['status'] = 'teacher';
            $data['photo']='http://graph.facebook.com/'. $this->user_info['fb_id'] . '/picture?type=large';
            $data['currentPage']=$this->getClassName();

            $this->view->renderHtml('common/head');
            $this->view->renderHtml('common/header', $data);
            $this->view->renderHtml('groups/archive', $data);
            $this->view->renderHtml('common/footer');
            $this->view->renderHtml('common/foot');

        } else {
            $this->view->renderHtml('error/access');
        }
    }

    public function moveToArchive()
    {
        $req = Request::getInstance();
        $groupId= $req->getParam(0);
        $value=$req->getParam(1);
        $this->model->archive($groupId,$value);
        $this->view->renderJson(Array('result'=>"success"));
    }


    public function getArchiveList(){
        $var = $this->model->getArchive();
        if(!is_null($var)){
            $var[count($var)]=Session::get('id');
            $this->view->renderJson($var);
        }
    }

    public function createNewGroup()
    {
        $name = Request::getPost('name');
        if (isset($name)) {
            if (count($name)) {

                if ($this->user_info['title'] == 'teacher') {

                    if ($this->model->existsGroup($name)) {
                        $this->view->renderJson(array(
                            'status' => 'groups_already_exists'
                        ));
                    } else {
                        $image = null;
                        $upload = new UploadImage($_FILES['photo']);
                        if ($upload->checkFileError() && $upload->upload()) {
                            $image = $upload->getUploadFileName();
                        } else {
                            if ($upload->getError() != 'File wasn\'t sent') {
                                $this->view->renderJson(array(
                                    'status' => 'sarfs' . $upload->getError()
                                ));
                                return;
                            }
                        }

                        $data = $this->model->createGroup($this->user_info['id'], $name, $image);
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