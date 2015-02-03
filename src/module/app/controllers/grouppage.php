<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 1/28/2015
 * Time: 3:53 PM
 */
class GroupPage extends Controller {
    //222222public $role='host';
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('grouppage');

    }

    public function index() {
        $req =Request::getInstance();
        $groupId= $req->getParam(0);
        $model = $this->loadModel('user');
        if(isset($_SESSION['fb_ID'])){
        $id=$_SESSION['fb_ID'];
        $id=$model->getInfoFB($id)['id'];
        }else{
            if(isset($_SESSION['gm_ID'])){
                $id=$_SESSION['gm_ID'];
                $id=$model->getInfoGM($id)['id'];
            }else return null;
        }
        $data['role'] = $this->model->getRole($groupId, $id) ; //викликаємо портрібні функції поделі
        $this->view->renderHtml('grouppage/index', $data);
    }
    public function delUser(){
        $req = Request::getInstance();
        $id= $req->getParam(0);
        $this->model->delUser($id);
        $this->view->renderJson(Array('result'=>"success"));
    }
    public function renameGroup(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        $newName= $req->getParam(1);
        $this->model->renameGroup($id, $newName);
        $this->view->renderJson(Array('result'=>"success"));

    }
    public  function createInviteCode(){
        $req= Request::getInstance();
        $id=$req->getParam(0);
        $this->model->createInviteCode($id);
        $this->view->renderJson(Array('code'=>$this->model->getInviteCode()));
    }
    public function editDescription(){
        $req= Request::getInstance();
        $id=$req->getParam(0);
        $newDescription = $req->getParam(1);
        $this->model->editDescription($id, $newDescription);
        $this->view->renderJson(Array('result'=>"success"));
    }
    public function sendSchedule(){
        $req= Request::getInstance();
        $id=$req->getParam(0);
        $var=$this->model->loadSchedule($id);
        $this->view->renderJson($var);
    }
    public function sendUsers(){
        $req=Request::getInstance();
        $id=$req->getParam(0);
        $var=$this->model->loadUsers($id);

        $this->view->renderJson($var);
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
        $id=$_SESSION['fb_ID'];
        if(!isset($id)){
            $error=3;
        }
        else{
        $id=$model->getInfoFB($id)['id'];
        $req=Request::getInstance();
        $code=$req->getParam(0);
        $error=$this->model->addUserToGroup($id, $code);
        }
        header("Content-Type: text/html; charset=utf-8");
        switch($error){
            case 1:
                $r=$r."Вы уже являетесь членом группы!";
                break;
            case 2:
                $r=$r."Invalid link!";
                break;
            case 3:
                $r=$r."Авторизируйтесь для продолжения";
                break;
            case 4:
                $r=$r."Преподаватель не может быть членом группы!";
                break;
            default:
                $r=$r."Теперь вы член группы!";
                break;
        }
        $r=$r.'<br/><a href="/src/app/calendar">Перейти на главную страницу</a></div>';
        echo $r;
    }

}
