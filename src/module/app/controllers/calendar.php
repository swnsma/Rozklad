<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 17:55
 * */

//Session::init();
class Calendar extends Controller {

    private $fb_id;
    private $userInfo;
    private $role='teacher';
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('user');
        $this->fb_id= 1;

        $this->userInfo=$this->model->getCurrentUserInfo();
        $this->role = $this->privateGetRole($this->userInfo[0]['title']);
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
        $data =$this->userInfo[0];
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
        $id=$this->model->addLesson($title,$start,$end,$this->userInfo[0]['id']);
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
        $this->model->updateLesson($title,$start,$end,$id);
        $this->view->renderJson("succeess");

    }

    //використовую
    public function addFullEvent(){
        $this->model = $this->loadModel('lesson');
        $start=Request::getInstance()->getParam(0);
        $end=Request::getInstance()->getParam(1);
        $id=$this->model->getOurLessonForThisId($this->userInfo[0]['id'],$start,$end);
        $this->view->renderJson($id);
    }

    //+

    public function getOurGroups(){
        $this->model = $this->loadModel('groups');
        $arr=$this->model->getOurGroups($this->userInfo[0]['id']);
        $this->view->renderJson($arr);
    }
    public function getGroups(){
        $this->model = $this->loadModel('groups');
//        print $this->userInfo['id'];
        $arr=$this->model->getGroups($this->userInfo[0]['id']);


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
        $id=$this->model->getRealTimeUpdate($interval,$this->userInfo[0]['id']);

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

    public function  getOurLessonForThisId(){
        $this->model = $this->loadModel('lesson');
        $this->model->getOurLessonForThisId($this->userInfo[0]['id']);
    }



    public function  getOurTeacher(){
        $req=Request::getInstance();
        $this->model = $this->loadModel('user');
        $id= $req->getParam(0);
        $date =$this->model->restore($id);
        $this->view->getOurTeacher($date);
    }

}
?>