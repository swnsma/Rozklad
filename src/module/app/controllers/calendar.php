<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 17:55
 * */

Session::init();
class Calendar extends Controller {

    private $fb_id;
    public $userInfo;
    private $role='teacher';
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('user');
        $this->fb_id= $_SESSION['idFB'];
        $this->userInfo=$this->model->getInfo($this->fb_id);
        $this->role = $this->privateGetRole($this->userInfo[0]['role_id']);
    }
    public function getRole(){
        return $this->role;
    }
    private function privateGetRole($role_id){
//        echo $role_id;
        if($role_id=='1'){
        return 'teacher';
    }
    else return 'student';
    }

    public function getUserInfo(){
        $this->view->renderJson($this->userInfo);
    }
    public function index() {
        $this->model = $this->loadModel('lesson');
        $data = $this->getRole();
//        echo $data;
        $this->view->renderHtml('calendar/index', $data);
    }
    public function addGroupToLesson(){
        $request=Request::getInstance();
        $lessonId = $request->getParam(0);
        $groupId = $request->getParam(1);
        $this->model=$this->loadModel("lesson");
        $success=$this->model->addGroupToLesson($lessonId,$groupId);
        echo $success;
    }
    public function addEvent(){
        $req=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $title= $req->getParam(0);
        $start= $req->getParam(1);
        $end= $req->getParam(2);
        $id=$this->model->addLesson($title,$start,$end,$this->userInfo[0]['id']);
        if($id==null){
            echo 'Ошибка';
        }else{
            $this->view->renderJson(array('id' => $id));
        }
    }
    public function updateEvent(){
        $req=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $title= $req->getParam(0);
        $start= $req->getParam(1);
        $end= $req->getParam(2);
        $id= $req->getParam(3);
        $this->model->updateLesson($title,$start,$end,$id);
        $this->view->renderJson("succeess");

    }

    public function addFullEvent(){
        $this->model = $this->loadModel('lesson');
        $start=Request::getInstance()->getParam(0);
        $end=Request::getInstance()->getParam(1);
        $id=$this->model->getAllEvent($start,$end);
       $this->view->renderJson($id);
    }
    public function getGroups(){
        $request=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $arr=$this->model->getGroups($request->getParam(0));
        $this->view->renderJson($arr);
    }
    public function getAllGroupsForThisLesson(){
        $request=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $arr=$this->model->getAllGroupsForThisLesson($request->getParam(0));
        $this->view->renderJson($arr);
    }
    public function getGroupsForLesson(){
        $request=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $this->model->getGroupsForLesson($request->getParam(0));
    }
    public function getRealTimeUpdate(){
        $this->model = $this->loadModel('lesson');
        $interval=Request::getInstance()->getParam(0);
        $id=$this->model->getRealTimeUpdate($interval);

        $this->view->renderJson($id);
    }

    public function delEvent(){
        $req=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $id= $req->getParam(0);
        $this->model->delEvent($id);

        $this->view->renderJson("success");
    }
    public function restore(){
        $req=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $id= $req->getParam(0);
        $date =$this->model->restore($id);

        $this->view->renderJson($date);
    }

}
?>