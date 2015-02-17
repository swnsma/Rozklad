<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 17:55
 * */

class Calendars extends Controller {

    private $userInfo;

    public function __construct() {
        parent::__construct();
        $id = $_SESSION['id'];
        if($id===null){
            $this->logout();
        }
        $this->model = $this->loadModel('user');
        $this->userInfo=$this->model->getCurrentUserInfo($id);
        if($this->userInfo===null){
            $this->logout();
        }
    }
    public function index() {
        $this->model = $this->loadModel('lesson');
//        $data =$this->userInfo;

        $data['title'] = "Calendar|Rozklad";
        $data['groups'] = $this->model->getList();
        $data['name'] = $this->userInfo['name'] . ' ' . $this->userInfo['surname'];
        $data['status'] = $this->userInfo['title'];
        $data['photo']='http://graph.facebook.com/'. $this->userInfo['fb_id'] . '/picture?type=large';
        /*$this->view->renderAllHTML('groups/index',
            $data,
            array('groups/groups.css'));*/
        $this->view->renderHtml('common/head',$data);
        $this->view->renderHtml('common/header', $data);
        $this->view->renderHtml('calendars/index', $data);
//        $this->view->renderHtml('common/footer');
        $this->view->renderHtml('common/foot');

    }

    public function getUserInformationAndOurGroups(){
       if($this->userInfo['title']==='teacher') {
           $this->model = $this->loadModel('groups');
           $returns['group'] = $this->model->getOurGroups();
           $returns['user'] = $this->userInfo;
           $returns['status'] = 'ok';
           $this->view->renderJson($returns);
       }else{
           $returns['status'] = 'noteacher';
           $this->view->renderJson($returns);
       }
    }

    public function getOurLessonForThisIdTeacherCurrent(){
        if($this->userInfo['title']==='teacher') {
            if(isset($_POST['start'])&&isset($_POST['end'])) {
                $this->model = $this->loadModel('lessons');
                $returns['data'] = $this->model->getOurLessonForThisIdTeacherCurrent($this->userInfo,$_POST['start'],$_POST['end']);
                $returns['status'] = 'ok';
                $this->view->renderJson($returns);
            }
        }else{
            $returns['status'] = 'noteacher';
            $this->view->renderJson($returns);
        }
    }

    public function getOurLessonForThisIdTeacherNoCurrent(){
        if($this->userInfo['title']==='teacher') {
            if(isset($_POST['start'])&&isset($_POST['end'])) {
                $this->model = $this->loadModel('lessons');
                $returns['data'] = $this->model->getOurLessonForThisIdTeacherNoCurrent($this->userInfo,$_POST['start'],$_POST['end']);
                $returns['status'] = 'ok';
                $this->view->renderJson($returns);
            }
        }else{
            $returns['status'] = 'noteacher';
            $this->view->renderJson($returns);
        }
    }

    public function getOurLessonForThisIdStudent(){
        if(isset($_POST['start'])&&$_POST['end']) {
            $this->model = $this->loadModel('lessons');
            $start = $_POST['start'];
            $end = $_POST['end'];
            $id['data'] = $this->model->getOurLessonForThisIdStudent($this->userInfo, $start, $end);
            $id['status']='ok';
            $this->view->renderJson($id);
        }
    }

    public function eventDrop(){

        if($this->userInfo['title']==='teacher'){

            if(isset($_POST['start'])&&isset($_POST['end'])&&isset($_POST['id'])){
                $start = $_POST['start'];
                $end = $_POST['end'];
                $idlesson = $_POST['id'];
                $this->model = $this->loadModel('lessons');


                if($this->model->eventDrop($idlesson, $start, $end)){
                    $id['status']='ok';
                    $this->view->renderJson($id);
                }else{
                    $id['status']='notOk';
                    $this->view->renderJson($id);
                }

            }else{
                $returns['status'] = 'problem';
                $this->view->renderJson($returns);
            }
        }else{
            $returns['status'] = 'noteacher';
            $this->view->renderJson($returns);
        }
    }

}