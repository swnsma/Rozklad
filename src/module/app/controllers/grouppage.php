<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 1/28/2015
 * Time: 3:53 PM
 */
require_once DOC_ROOT . 'core/UploadImage.php';
class GroupPage extends Controller {
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('grouppage');

    }
    public function index() {
        $req =Request::getInstance();
        $groupId= $req->getParam(0);
        $model = $this->loadModel('user');
        $flag=false;
        if(Session::has('fb_ID')){
        $id=Session::get('fb_ID');
        $id=$model->getInfoFB($id)['id'];
        $flag=true;
        }else{
            if(Session::has('gm_ID')){
                $id=Session::get('gm_ID');
                $id=$model->getInfoGM($id)['id'];
                $flag=true;
            }
        }
        if($this->model->existGroup($groupId)){
        if($flag){
            $user= $model->getCurrentUserInfo($id);
            $data['name']=$user["name"].' '.$user["surname"];
            $data['status']=$user["title"];
            $data['photo']='http://graph.facebook.com/'.$user['fb_id'].'/picture?type=large';        $this->view->renderHtml('common/head');
        $this->view->renderHtml('common/header', $data);
            $data['role'] = $this->model->getRole($groupId, $id) ; //викликаємо портрібні функції поделі
            $data['id']=$id;
        }else{
            $data['role']=null;
        }

            $this->view->renderHtml('grouppage/index', $data);
        }else{
            $data['id']=$groupId;
            $this->view->renderHtml('grouppage/404', $data);
        }
    }
    public function delUser(){
        $req = Request::getInstance();
        $groupId= $req->getParam(1);
        $id=$req->getParam(0);
        $this->model->delUser($id, $groupId);
        $this->view->renderJson(Array('result'=>"success"));
    }
    public function renameGroup(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        if(isset($_POST['title'])&&$_POST['title']){
        $title = $_POST['title'];
        if($this->model->checkName($title)){
            $this->view->renderJson(array('errormess'=>"Группа с данным именем уже существует"));
            return;
        }
            $this->model->renameGroup($id, $title);
        }
        $this->view->renderJson(Array('result'=>"success"));

    }
    public function createInviteCode(){
        $req= Request::getInstance();
        $id=$req->getParam(0);
        $this->model->createInviteCode($id);
        $this->view->renderJson(Array('code'=>$this->model->getInviteCode()));
    }
    public function editDescription(){
        $req= Request::getInstance();
        $id=$req->getParam(0);
        $newDescription = $_POST['data'];

        $this->model->editDescription($id, $newDescription);
        $this->view->renderJson(Array('result'=>"success"));
    }
    public function sendUsers(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        $var=$this->model->loadUsers($id);

        $this->view->renderJson($var);
    }
    public function changeImage(){
        $req = Request::getInstance();
        $id = $req->getParam(0);
        if(isset($_POST['title'])&&$_POST['title']){
        $title = $_POST['title'];
            if($this->model->checkName($title)){
                $this->view->renderJson(array('errormess'=>"Группа с данным именем уже существует"));
                return;
            }
            $this->model->renameGroup($id, $title);
        }
        if(isset($_POST['data'])&&$_POST['data']){
        $desc = $_POST['data'];
        $this->model->editDescription($id, $desc);
        }
        $a= $_FILES['photo'];
        $upload = new UploadImage($a);
        if ($upload->checkFileError() && $upload->upload()) {
            $image = $upload->getUploadFileName();
        } else {
            if ($upload->getError() != 'File wasn\'t sent') {
                $this->view->renderJson(array(
                    'status' => $upload->getError()
                ));
                return;
            }
        }
        $this->model->deletePhoto($id);
        $this->model->changeImage($id, $image);
        $this->view->renderJson(array("result"=>$image, "title"=>$_POST['title']));;


    }
    public function sendGroupInfo(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        $var=$this->model->getGroupInfo();
        if(!isset($var)){
            $var=$this->model->loadData($id);
        }
        $this->view->renderJson($var);
    }
    public function sendCode(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        $var=$this->model->getInviteCode($id);
        if(!isset($var)){
            $var=$this->model->loadCode($id);
        }
        $this->view->renderJson(Array('code'=>$var));
    }
    public function inviteUser(){
        $model = $this->loadModel('user');
        $r='<div style="text-align:center">';
        $outlink = URL.'app/signin';

        $error=0;
        if(Session::has('fb_ID'))
        {
            $id=Session::get('fb_ID');
            $id=$model->getInfoFB($id)['id'];
        }else{
            if(Session::has('gm_ID')){
                $id=Session::get('gm_ID');
                $id=$model->getInfoGM($id)['id'];
            }
        }
        $req=Request::getInstance();
        $code=$req->getParam(0);
        if(!$error){
        $groupInfo=$this->model->getGroupByCode($code);
            $error=$this->model->addUserToGroup($id, $code);
        $name=$groupInfo['name'];
        }
        $link = URL.'app/grouppage/'.'id'.$groupInfo['id'];
        header("Content-Type: text/html; charset=utf-8");
        switch($error){
            case 1:
                header("Refresh: 3; url=$link");
                $r=$r."Вы уже являетесь членом группы $name!<br/><a href=".'"'.$link.'"> Перейти к странице группы</a>';
                break;
            case 2:
                header("Refresh: 3; url=$outlink");
                $r=$r."Invalid link!";
                break;
            case 4:
                header("Refresh: 3; url=$link");
                $r=$r."Преподаватель не может быть членом группы!<br/><a href=".'"'.$link.'"> Перейти к странице группы</a>';
                break;
            default:
                header("Refresh: 3; url=$link");
                $r=$r."Теперь вы член группы $name!<br/><a href=".'"'.$link.'"> Перейти к странице группы</a>';
                break;
        }
        $r=$r.'<br/><a href="'.URL.'app/calendar">Перейти на главную страницу</a></div>';
        echo $r;
    }
    public function restore(){
        $req=Request::getInstance();
        $groupId=$req->getParam(0);
        $userId=$req->getParam(1);
        $this->model->addUser($groupId, $userId);
    }
}
