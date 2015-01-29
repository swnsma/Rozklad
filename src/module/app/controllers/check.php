<?php

require_once( FILE.'facebook/HttpClients/FacebookHttpable.php' );
require_once( FILE.'facebook/HttpClients/FacebookCurl.php' );
require_once(FILE.'facebook/HttpClients/FacebookCurlHttpClient.php' );
require_once( FILE.'facebook/Entities/AccessToken.php' );
require_once( FILE.'facebook/Entities/SignedRequest.php');
require_once( FILE.'facebook/FacebookSession.php' );
require_once( FILE.'facebook/FacebookSignedRequestFromInputHelper.php');
require_once( FILE.'facebook/FacebookCanvasLoginHelper.php');
require_once( FILE.'facebook/FacebookRedirectLoginHelper.php' );
require_once( FILE.'facebook/FacebookRequest.php' );
require_once( FILE.'facebook/FacebookResponse.php' );
require_once( FILE.'facebook/FacebookSDKException.php' );
require_once( FILE.'facebook/FacebookRequestException.php' );
require_once( FILE.'facebook/FacebookOtherException.php' );
require_once(FILE.'facebook/FacebookAuthorizationException.php' );
require_once( FILE.'facebook/GraphObject.php' );
require_once(FILE.'facebook/GraphUser.php');
require_once( FILE.'facebook/GraphSessionInfo.php' );
require_once(FILE.'facebook/FacebookJavaScriptLoginHelper.php' );

use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;
use Facebook\FacebookSession;
use Facebook\FacebookSignedRequestFromInputHelper;
use Facebook\FacebookCanvasLoginHelper;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;

class Check extends Controller
{
    private $model;

    public function __constructor()
    {
        parent::__construct();
    }

    public function check()
    {
        $id = '1536442079974268'; // please use yours
        $secret = '1d75987fcb8f4d7abc1a34287f9601cf'; // please use yours
        FacebookSession::setDefaultApplication($id, $secret);
        $helper = new \Facebook\FacebookJavaScriptLoginHelper();
        // see if a existing session exists
        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            // create new session from saved access_token
            $session = new FacebookSession($_SESSION['fb_token']);
            // validate the access_token to make sure it's still valid
            try {
                if (!$session->validate($id, $secret)) {
                    $helper = new \Facebook\FacebookJavaScriptLoginHelper();
                    $session = $helper->getSession();
                    $_SESSION['fb_token'] = $session->getToken();
                }
            } catch (Exception $e) {
                $session = null;
            }
        } else {

            try {
                $session = $helper->getSession();
            } catch (FacebookRequestException $ex) {

            }
        }


        if (isset($session)) {

            $_SESSION['fb_token'] = $session->getToken();

            $session = new FacebookSession($_SESSION['fb_token']);

            $request = new FacebookRequest($session, 'GET', '/me');
            $response = $request->execute();
            $graphObject = $response->getGraphObject()->asArray();

            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();

            $_SESSION['FB'] = true;

            $_SESSION['usernameFB'] = $graphObject['name'];
            $_SESSION['idFB'] = $graphObject['id'];
            $_SESSION['first_nameFB'] = $graphObject['first_name'];
            $_SESSION['last_nameFB'] = $graphObject['last_name'];
            $_SESSION['genderFB'] = $graphObject['gender'];

            $this->model = $this->loadModel("check");
            $inBase = $this->model->checkUserFB($_SESSION['idFB']);
            if($inBase)
            {
                return "ok";
            }
            else {
                return "regist";
            }
        } else {
            $this->unset_cookie();
            return "not";
        }
    }

    public function logout()
    {
        $this->unset_cookie();
        echo "logout!";
    }
    public function addUserFB(){
        $request=Request::getInstance();
        $name = $request->getParam(0);
        $surname =$request->getParam(1);
        $phone =$request->getParam(2);
        $role =$request->getParam(3);
        $this->model=$this->loadModel("regist");
        $fb_id=$_SESSION['idFB'];
        $existUser = $this->model->checkUserFB($fb_id);
        if(!$existUser) {
            $reg=$this->model->addUserFB($name, $surname, $phone,$role, $fb_id);
            if($reg){
                echo "registed";
            }else{
                echo "not_registed";
            }
        }
        else echo "not";
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
}
?>