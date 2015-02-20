<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 12.02.2015
 * Time: 11:15
 */
class Lesson extends Controller {
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
    public function getUserInfo(){
        $this->view->renderJson($this->userInfo);
    }
    public function index() {
        $req =Request::getInstance();
        $lessonId= $req->getParam(0);
        $this->model = $this->loadModel('lesson');
        if($this->model->existLesson($lessonId,$this->userInfo)) {
            $data['title'] = "Lesson|Rozklad";
            $data['id']=$this->userInfo['id'];
            $data['email']=$this->userInfo['email'];
            $data['name'] = $this->userInfo['name'] . ' ' . $this->userInfo['surname'];
            $data['status'] = $this->userInfo['title'];
            $data['photo'] = 'http://graph.facebook.com/' . $this->userInfo['fb_id'] . '/picture?type=large';

            $this->view->renderHtml('common/head', $data);
            $this->view->renderHtml('common/header', $data);
            $this->view->renderHtml('lesson/index',$data);
        }else{
            $data['id']=$lessonId;
            $this->view->renderHtml('lesson/404',$data);
        }

    }
    public function getLessonInfo(){
        $req = Request::getInstance();
        $lessonId= $req->getParam(0);
        $var = $this->model->getInfo($lessonId);
        if(isset($var)){

            $this->view->renderJson($var);
        }
    }
    public function changeLessonInfo(){
        $req = Request::getInstance();
        $lessonId= $req->getParam(0);
        $value=$_POST['data'];
        $this->model->newInfo($lessonId,$value);
        $this->view->renderJson(Array('result'=>"success"));
    }
    public function uploadTask(){
        $file=$_FILES["file"]["name"];
        if(isset ($file)) {
            $fileName = $_FILES["file"]["name"];
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileTmpLoc = $_FILES["file"]["tmp_name"];
            $name = uniqid() . '.' . $ext;
            $pathAndName = TASKS_FOLDER . $name;
            move_uploaded_file($fileTmpLoc, $pathAndName);
            $this->view->renderJson(Array('newName' => $name, 'oldName' => $fileName));
        }
    }
    public function deleteFile(){
        $fileName=$_POST['data'];
        $pathAndName=TASKS_FOLDER.'/'.$fileName ;
        unlink ($pathAndName);
        $this->view->renderJson(Array('result'=>"success"));
    }
    public function uploadHomework(){
        $req = Request::getInstance();
        $studentId= $req->getParam(0);
        $lessonId=$req->getParam(1);
        $file=$_FILES["file"]["name"];
        if(isset ($file)){
        $fileName = $_FILES["file"]["name"];
        $ext=pathinfo($fileName, PATHINFO_EXTENSION);
        $fileTmpLoc = $_FILES["file"]["tmp_name"];
        $name=uniqid(). '.' . $ext;
        $pathAndName = HOMEWORK_FOLDER.'/'.$name ;
        move_uploaded_file($fileTmpLoc, $pathAndName);
            $this->model->saveTask($studentId,$name,$lessonId);
        $this->view->renderJson(Array('newName'=>$name));
        }
    }
    public function getTasks(){
        $req = Request::getInstance();
        $lessonId= $req->getParam(0);
        $var=$this->model->loadTasks($lessonId);
        if(isset($var)){
            $this->view->renderJson($var);
        }

    }


}
