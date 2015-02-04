<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 17:55
 * */

//Session::init();
class Calendar extends Controller {

    private $userInfo;
    private $role='teacher';
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('user');

        $this->userInfo=$this->model->getCurrentUserInfo();
//        print_r($this->userInfo);
//        exit;
//        $this->view->renderHtml($this->userInfo);
    }
    public function getRole(){
        return $this->role;
    }
    //використовую
    private function privateGetRole($role_id){
//        echo $role_id;
        if($role_id=='1'){
            return 'teacher';
        }
        else return 'student';
    }

    //використовую
    public function getUserInfo(){
        $this->view->renderJson($this->userInfo);
    }

    //використовую
    public function index() {
        $this->model = $this->loadModel('lesson');
        $data =$this->userInfo;
        $this->view->renderHtml('calendar/index', $data);
    }

    //моє
    public function addGroupsToLesson(){
        $request=Request::getInstance();
        $lessonId = $request->getParam(0);
        $var =$request->getParams();

        $this->model=$this->loadModel("grouplesson");
//        echo $request->getParam(0);
//        print_r($var);
        for($i=1;$i<count($var);++$i){
//            echo $var[$i];
            $success=$this->model->addGroupToLesson($lessonId,$var[$i]);
        }


//        echo $success;
        $this->view->renderJson(Array('success'=>'success'));
    }

    //+
    public function deleteGroupFromLesson(){
        $request=Request::getInstance();
        $lessonId = $request->getParam(0);
        $var =$request->getParams();
        $this->model=$this->loadModel("lesson");
        for($i=1;$i<count($var);++$i){
            $success=$this->model->deleteGroupFromLesson($lessonId,$var[$i]);
        }

        $this->view->renderJson(Array('success'=>$success));
    }

    //використовую
    public function addEvent(){
        $req=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $title= $req->getParam(0);
        $start= $req->getParam(1);
        $end= $req->getParam(2);
        $teacher= $req->getParam(3);
//        echo $teacher;
        $id=$this->model->addLesson($title,$start,$end,$teacher);

//        exit;
        if($id==null){
            echo 'Ошибка';
        }else{
            $this->view->renderJson(array('id' => $id));
        }
    }

    //використовую
    public function updateEvent(){
        $req=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $title= $req->getParam(0);
        $start= $req->getParam(1);
        $end= $req->getParam(2);
        $id= $req->getParam(3);
        $teacherId= $req->getParam(4);
        $this->model->updateLesson($title,$start,$end,$id,$teacherId);
        $this->view->renderJson("succeess");

    }

    //використовую
    public function addFullEvent(){
        $this->model = $this->loadModel('lesson');
        $start=Request::getInstance()->getParam(0);
        $end=Request::getInstance()->getParam(1);
        $id=$this->model->getOurLessonForThisId($this->userInfo,$start,$end);
        $this->view->renderJson($id);
    }

    //+

    public function getOurGroups(){
        $this->model = $this->loadModel('groups');
        $arr=$this->model->getOurGroups($this->userInfo['id']);
        $this->view->renderJson($arr);
    }
    public function getGroups(){
        $this->model = $this->loadModel('groups');
//        print $this->userInfo['id'];
        $arr=$this->model->getGroups($this->userInfo['id']);


        $this->view->renderJson($arr);
    }

    //+
    public function getAllGroupsForThisLesson(){
        $request=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $arr=$this->model->getAllGroupsForThisLesson($request->getParam(0));
//        echo $arr;
        $this->view->renderJson($arr);
    }


    //використовую
    public function getRealTimeUpdate(){
        $this->model = $this->loadModel('lesson');
        $interval=Request::getInstance()->getParam(0);

//        print_r($this->userInfo);
        $id=$this->model->getRealTimeUpdate($interval,$this->userInfo);

        $this->view->renderJson($id);
    }

    //використовую
    public function delEvent(){
        $req=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $id= $req->getParam(0);
        $this->model->delEvent($id);

        $this->view->renderJson("success");
    }

    //використовую
    public function restore(){
        $req=Request::getInstance();
        $this->model = $this->loadModel('lesson');
        $id= $req->getParam(0);
        $date =$this->model->restore($id);
        $this->view->renderJson($date);
    }

    public function  getOurTeacher(){
        $this->model = $this->loadModel('user');
        $date=$this->model->getOurTeacher();
//        echo $date;
        $this->view->renderJson($date);
    }

}
?>