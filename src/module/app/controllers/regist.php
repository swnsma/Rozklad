<?php
class Regist extends Controller
{
    private $model;

    public function __constructor()
    {
        parent::__construct();
    }
    public function index(){
        header('Content-type: text/html; charset=utf-8');
        $this->view->renderHtml("regist/index");
    }
    public function addUser(){
        $request=Request::getInstance();
        $name = $request->getParam(0);
        $surname =$request->getParam(1);
        $phone =$request->getParam(2);
        $role =$request->getParam(3);
        $this->model=$this->loadModel("regist");
        $fb_ID='';
        if(isset($_SESSION['fb_ID'])){
            $fb_ID=$_SESSION['fb_ID'];
        }
        $gm_ID='';
        if(isset($_SESSION['gm_ID'])){
            $gm_ID=$_SESSION['gm_ID'];
        }
        $email=$_SESSION['email'];
        $existUserFb=0;
        $existUserGm=0;
        if($fb_ID) {
            $existUserFb = $this->model->checkUserFB($fb_ID);
        }
        if($gm_ID) {
            $existUserGm = $this->model->checkUserGM($gm_ID);
        }
        if((!$existUserFb)&&(!$existUserGm)) {
            $reg=$this->model->addUser($name, $surname, $phone,$role, $fb_ID,$gm_ID,$email);
            if($reg){
                if(isset($_SESSION['fb_ID'])){
                    $this->model=$this->loadModel("user");
                    $id=$this->model->getIdFB($_SESSION["fb_ID"]);
                    $_SESSION['id']=$id;
                }
                if(isset($_SESSION['gm_ID'])){
                    $this->model=$this->loadModel("user");
                    $id=$this->model->getIdGM($_SESSION["gm_ID"]);
                    $_SESSION['id']=$id;
            }
                echo "registed";
            }else{
                echo "not_registed";
            }
        }
        else echo $existUserFb;
    }

    private function unset_cookie()
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
        }
    }

    public function back_signin(){
        $_SESSION['status']='not';
        header("Location:".$_SESSION['logout_link']);
        exit;
    }

}
?>