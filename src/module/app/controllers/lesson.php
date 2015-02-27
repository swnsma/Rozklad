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
        $id = Session::get('id');
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
            $user = array(
                "id" => $data['id'],
                "username" => $data['name'],
                "email" => $data['email']
            );
            $message = base64_encode(json_encode($user));
            $timestamp = time();
            $data["message"]=$message;
            $data["timestamp"]=$timestamp;
            $data["hmac"]=$this->dsq_hmacsha1($message . ' ' . $timestamp, DISQUS_PUBLIC_KEY);
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

    private function dsq_hmacsha1($data, $key) {
        $blocksize=64;
        $hashfunc='sha1';
        if (strlen($key)>$blocksize)
            $key=pack('H*', $hashfunc($key));
        $key=str_pad($key,$blocksize,chr(0x00));
        $ipad=str_repeat(chr(0x36),$blocksize);
        $opad=str_repeat(chr(0x5c),$blocksize);
        $hmac = pack(
            'H*',$hashfunc(
                ($key^$opad).pack(
                    'H*',$hashfunc(
                        ($key^$ipad) . $data
                    )
                )
            )
        );
        return bin2hex($hmac);
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


    public function getAll(){
        $req = Request::getInstance();
        $lessonId= $req->getParam(0);
        $var= array();
        $var['tasks']=$this->model->loadTasks($lessonId);
        $dead =$this->model->getDeadLine($lessonId);
        $time = date("d-m-Y H:i");
        $var['deadLine']=Array('result'=>$dead, 'time'=>$time);
        $var['lessonInfo'] = $this->model->getInfo($lessonId);
        $this->view->renderJson($var);
    }
    

    public function setDeadLine(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        $line = $_POST['deadline'];
        $this->model->setDeadLine($id, $line);
        $this->view->renderJson(Array('result'=>$line, 'id'=>$id));
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

    function setLastVisit(){
        $date = strtotime($_POST['date']);
        $user_id = Session::get('id');
        $lesson_id = $_POST['lesson_id'];
        $this->model=$this->loadModel("lesson");
        $result = $this->model->setLastVisit($user_id,$lesson_id,$date);
        $this->view->renderJson($result);
    }

    public function allLessons(){
        $this->model=$this->loadModel("user");
        $userInfo=$this->model->getCurrentUserInfo();
        $this->model=$this->loadModel("lesson");
        $result = $this->model->unreadedMessages($userInfo);
        $this->view->renderJson($result);
    }

    function setRate(){
        $value=$_POST;
        $grade=$value['data']['grade'];
        $lessonId=$value['data']['lessonId'];
        $teacherName=$value['data']['teacherName'];
        $this->model->grade($teacherName,$lessonId,$grade);
        $this->view->renderJson(Array('result'=>'success'));
    }

    public function saveMess(){
        $post = $_POST;
        $lesson_id = $post['lesson_id'];
        $user_id  = Session::get('id');
        $date = $post['date'];
        $text = $post['text'];
        $this->model=$this->loadModel("lesson");
        $res=$this->model->saveMess($lesson_id,$user_id,$date,$text);
        $this->view->renderJson($res);
    }

    public function getAllCommentsForLesson(){
        $get = $_GET;
        $lesson_id = $get['lesson_id'];
        $since = $get['since'];
        $this->model=$this->loadModel("lesson");
        $res=$this->model->getAllCommentsForLesson($lesson_id, $since);
        $this->view->renderJson($res);
    }

    public function unreaded(){
        $this->model=$this->loadModel("user");
        $userInfo=$this->model->getCurrentUserInfo();
        $this->model=$this->loadModel("lesson");
        $result = $this->model->allUnreaded($userInfo);
        $this->view->renderJson($result);
    }

    public function realTimeUpdate(){
        $since = $_POST["since"];
        $this->model=$this->loadModel("user");
        $userInfo=$this->model->getCurrentUserInfo();
        $this->model=$this->loadModel("lesson");
        $result = $this->model->allUnreaded($userInfo, $since);
        $this->view->renderJson($result);
    }
}
