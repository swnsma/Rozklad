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
    public function updateGM(){

    }
    public function updateFB(){

    }
//    public function check()
//    {
//        $id = '384838578363750'; // please use yours
//        $secret = 'a9ebc86bba6ecd938e3fcd0c956c36a6'; // please use yours
//        FacebookSession::setDefaultApplication($id, $secret);
//        $helper = new \Facebook\FacebookJavaScriptLoginHelper();
//        // see if a existing session exists
//        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
//            // create new session from saved access_token
//            $session = new FacebookSession($_SESSION['fb_token']);
//            // validate the access_token to make sure it's still valid
//            try {
//                if (!$session->validate($id, $secret)) {
//                    $helper = new \Facebook\FacebookJavaScriptLoginHelper();
//                    $session = $helper->getSession();
//                    $_SESSION['fb_token'] = $session->getToken();
//                }
//            } catch (Exception $e) {
//                $session = null;
//            }
//        } else {
//
//            try {
//                $session = $helper->getSession();
//            } catch (FacebookRequestException $ex) {
//
//            }
//        }
//
//
//        if (isset($session)) {
//
//            $_SESSION['fb_token'] = $session->getToken();
//
//            $session = new FacebookSession($_SESSION['fb_token']);
//
//            $request = new FacebookRequest($session, 'GET', '/me');
//            $response = $request->execute();
//            $graphObject = $response->getGraphObject()->asArray();
//
//            $_SESSION['valid'] = true;
//            $_SESSION['timeout'] = time();
//
//            $_SESSION['FB'] = true;
//
//            $_SESSION['usernameFB'] = $graphObject['name'];
//            $_SESSION['idFB'] = $graphObject['id'];
//            $_SESSION['first_nameFB'] = $graphObject['first_name'];
//            $_SESSION['last_nameFB'] = $graphObject['last_name'];
//            $_SESSION['genderFB'] = $graphObject['gender'];
//
//            $this->model = $this->loadModel("check");
//            $inBase = $this->model->checkUserFB($_SESSION['idFB']);
//            if($inBase)
//            {
//                return "ok";
//            }
//            else {
//                return "regist";
//            }
//        } else {
//            $this->unset_cookie();
//            return "not";
//        }
//    }

    public function addUser(){
        $request=Request::getInstance();
        $name = $request->getParam(0);
        $surname =$request->getParam(1);
        $phone =$request->getParam(2);
        $role =$request->getParam(3);
        $this->model=$this->loadModel("regist");
        $fb_ID=$_SESSION['fb_ID']?$_SESSION['fb_ID']:'';
        $gm_ID=$_SESSION['gm_ID']?$_SESSION['gm_ID']:'';
        $existUserFb=0;
        $existUserGm=0;
        if($fb_ID) {
            $existUserFb = $this->model->checkUserFB($fb_ID);
        }
        if($gm_ID) {
            $existUserGm = $this->model->checkUserGM($gm_ID);
        }
        if((!$existUserFb)&&(!$existUserGm)) {
            $reg=$this->model->addUser($name, $surname, $phone,$role, $fb_ID,$gm_ID);
            if($reg){
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