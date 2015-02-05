<?php

//require_once FILE.'conf/setup.php';
require_once FILE .'lib/google/Google_Client.php';
require_once FILE .'lib/google/Google_Oauth2Service.php';


class Loging extends Controller {
    public static $status;
    private $client;
    private $oauth2;
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('check');
        $this->client = new Google_Client();
        $this->client->setApplicationName("Idiot Minds Google Login Functionallity");
        $this->client->setClientId(CLIENT_ID_GM);
        $this->client->setClientSecret(CLIENT_SECRET_GM);
        $this->client->setRedirectUri(URL . "app/loging/login");
        $this->client->setApprovalPrompt(APPROVAL_PROMPT);
        $this->client->setAccessType(ACCESS_TYPE);
        $this->oauth2 = new Google_Oauth2Service($this->client);
    }

    public function index() {
//        $this->view->renderHtml('signin/index');
    }

    public function login(){
        if (isset($_GET['code'])) {
            $this->client->authenticate($_GET['code']);
            $_SESSION['token'] = $this->client->getAccessToken();
        }
        if (isset($_SESSION['token'])) {
            $this->client->setAccessToken($_SESSION['token']);
        }
        if (isset($_REQUEST['error'])) {
            echo '<script type="text/javascript">window.close();</script>'; exit;
        }
        if ($this->client->getAccessToken()) {
            $user_g = $this->oauth2->userinfo->get();
            $_SESSION['user']=$user_g;
            $_SESSION['lastname']=$user_g['family_name'];
            $_SESSION['firstname']=$user_g['given_name'];
            $_SESSION['gm_ID']= $_SESSION['user']['id'];
            $_SESSION['email']=$_SESSION['user']['email'];
            $_SESSION['gm_token'] = $this->client->getAccessToken();
            $_SESSION['logout_link']="http://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://localhost/src/app/loging/logout";
            $status=$_SESSION['status'];
            $this->checkUser();
            exit;
        } else {
            $authUrl = $this->client->createAuthUrl();
            header("Location:".$authUrl);
        }

    }
    public function login_gm(){
        $_SESSION['status']='update';
        $this->login();
    }

    public function checkUser(){
//        print_r($_SESSION["gm_ID"]);
        $check= $this->model->checkUserGM($_SESSION["gm_ID"]);
        if($check){
            $this->model=$this->loadModel("user");
            $id=$this->model->getIdGM($_SESSION["gm_ID"]);
            $_SESSION['id']=$id;
            $link=URL."app/calendar";
            if(isset($_SESSION['unusedLink'])){
                $link=URL.$_SESSION['unusedLink'];
                $_SESSION['unusedLink']="";
            }
            header("Location:".$link);
            exit;
        }
        else {
            $_SESSION['status']='regist';
            $this->model=$this->loadModel("check");
            if($this->model->checkEmail($_SESSION['email'])){
                $this->model=$this->loadModel("regist");
                $this->model->updateGM($_SESSION['gm_ID'],$_SESSION['email']);
                $this->model=$this->loadModel("user");
                $id=$this->model->getIdGM($_SESSION["gm_ID"]);
                $_SESSION['id']=$id;
                header("Location:".URL."app/calendar");
                exit;
            }
            else{
                header('Content-type: text/html; charset=utf-8');
                header("Location:".URL."app/regist");
                exit;
            }
        }

    }
    public function logoutb(){
        echo "<a  href='https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://localhost/src/app/loging/logout'>Logout</a>";
    }
    public function logout(){
        session_destroy();
        $_SESSION['login']=0;
        header("Location:".URL."app/signin");
    }
    private function checkEmail($email){
        $this->model=$this->loadModel("check");
        return $this->model->checkEmail($email);
    }
}

?>