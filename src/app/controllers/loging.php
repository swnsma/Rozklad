<?php

require_once DOC_ROOT . 'lib/google/Google_Client.php';
require_once DOC_ROOT . 'lib/google/Google_Oauth2Service.php';


class Loging extends Controller
{
    public static $status;
    private $client;
    private $oauth2;
    private $modelCheck;
    private $modelUser;
    private $modelRegist;

    public function __construct()
    {
        parent::__construct();
        Session::uns("fb_ID");
        $this->modelCheck = $this->loadModel('check');
        $this->modelUser = $this->loadModel('user');
        $this->client = new Google_Client();
        $this->client->setApplicationName("Rozklad");
        $this->client->setClientId(CLIENT_ID_GM);
        $this->client->setClientSecret(CLIENT_SECRET_GM);
        $this->client->setRedirectUri(URL . "app/loging/login");
        $this->client->setApprovalPrompt(APPROVAL_PROMPT);
        $this->client->setAccessType(ACCESS_TYPE);
        $this->client->setScopes([
            'https://www.googleapis.com/auth/plus.login'
            ,'https://www.googleapis.com/auth/plus.me'
            ,'https://www.googleapis.com/auth/userinfo.email'
            ,'https://www.googleapis.com/auth/calendar']);

        $this->oauth2 = new Google_Oauth2Service($this->client);
    }

    public function index() {}

    public function login()
    {
        if (isset($_GET['code'])) {
            $this->client->authenticate($_GET['code']);
            Session::set('token',$this->client->getAccessToken());
        }
        if (Session::has('token')) {
            $this->client->setAccessToken(Session::get('token'));
        }
        if (isset($_REQUEST['error'])) {
            echo '<script type="text/javascript">window.close();</script>'; exit;
        }
        if ($this->client->getAccessToken()) {
            $user_g = $this->oauth2->userinfo->get();
            Session::set("user",$user_g);
            Session::set('lastname',$user_g['family_name']);
            Session::set('firstname',$user_g['given_name']);
            Session::set('gm_ID',Session::get('user')['id']);
            if(isset(Session::get('user')['email'])){
                Session::set('email',Session::get('user')['email']);
            }
            else{
                Session::set('email',NULL);
            }
            Session::set('gm_token',$this->client->getAccessToken());
//            Session::set('logout_link',"http://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://localhost/src/app/loging/logout");
            $this->checkUser();
            exit;
        } else {
            $authUrl = $this->client->createAuthUrl();
            header("Location:".$authUrl);
        }
    }

    public function checkUser()
    {
        $hasUser= $this->modelCheck->checkUserGM(Session::get("gm_ID"));
        if($hasUser) {
            $id=$this->modelUser->getIdGM(Session::get("gm_ID"));
            Session::set('id',$id);
            Session::set('status',"ok");
            $link="app/calendar";
            if(Session::has('unusedLink')) {
                $link = Session::get('unusedLink');
            }
            $isUnconf=$this->modelUser->checkUnconfirmed($id);
            if($isUnconf) {
                Session::set('status',"unconfirmed");
            }
            header("Location:".URL.$link);
            exit;
        } else {
            Session::set('status','regist');
            if(Session::has('email')&&Session::get('email')!='') {
                if ($this->modelCheck->checkEmail(Session::get('email'))) {
                    $this->modelRegist = $this->loadModel('regist');
                    $this->modelRegist->updateGM(Session::get('gm_ID'), Session::get('email'));
                    $id = $this->modelUser->getIdGM(Session::get("gm_ID"));
                    Session::set('id', $id);
                    Session::set('status', "ok");
                    $link="app/calendar";
                    if(Session::has('unusedLink')){
                        $link = Session::get('unusedLink');
                        Session::uns('unusedLink');
                    }
                    $isUnconf=$this->modelUser->checkUnconfirmed($id);
                    if($isUnconf){
                        Session::set('status',"unconfirmed");
                    }
                    header("Location:" . URL . $link);
                    exit;
                } else {
                    header('Content-type: text/html; charset=utf-8');
                    header("Location:".URL."app/regist");
                    exit;
                }
            } else {
                header('Content-type: text/html; charset=utf-8');
                header("Location:".URL."app/regist");
                exit;
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location:".URL);
    }

    private function checkEmail($email)
    {
        return $this->modelCheck->checkEmail($email);
    }
}