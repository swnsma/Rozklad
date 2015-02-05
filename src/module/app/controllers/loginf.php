<?php
//require_once DOC_ROOT .'conf/setup.php';
require_once(DOC_ROOT . 'lib/facebook/HttpClients/FacebookHttpable.php' );
require_once(DOC_ROOT . 'lib/facebook/HttpClients/FacebookCurl.php' );
require_once(DOC_ROOT . 'lib/facebook/HttpClients/FacebookCurlHttpClient.php' );
require_once(DOC_ROOT . 'lib/facebook/Entities/AccessToken.php' );
require_once(DOC_ROOT . 'lib/facebook/Entities/SignedRequest.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookSession.php' );
require_once(DOC_ROOT . 'lib/facebook/FacebookSignedRequestFromInputHelper.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookCanvasLoginHelper.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookRedirectLoginHelper.php' );
require_once(DOC_ROOT . 'lib/facebook/FacebookRequest.php' );
require_once(DOC_ROOT . 'lib/facebook/FacebookResponse.php' );
require_once(DOC_ROOT . 'lib/facebook/FacebookSDKException.php' );
require_once(DOC_ROOT . 'lib/facebook/FacebookRequestException.php' );
require_once(DOC_ROOT . 'lib/facebook/FacebookOtherException.php' );
require_once(DOC_ROOT . 'lib/facebook/FacebookAuthorizationException.php' );
require_once(DOC_ROOT . 'lib/facebook/GraphObject.php' );
require_once(DOC_ROOT . 'lib/facebook/GraphUser.php');
require_once(DOC_ROOT . 'lib/facebook/GraphSessionInfo.php' );
require_once(DOC_ROOT . 'lib/facebook/FacebookJavaScriptLoginHelper.php' );

require_once (DOC_ROOT.'module/app/controllers/signin.php');

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


class Loginf extends Controller {
    private $model;

    public function __construct() {
        parent::__construct();
        $this->model=$this->loadModel("check");
        FacebookSession::setDefaultApplication( APP_ID_FB,APP_SECRET_FB );
    }
    public function index() {
//        $this->view->renderHtml('signin/index');
    }
    public function login(){
// login helper with redirect_uri
        $helper = new FacebookRedirectLoginHelper(URL."app/loginf/login" );
        try {
            $session = $helper->getSessionFromRedirect();
        } catch( FacebookRequestException $ex ) {
            // When Facebook returns an error
        } catch( Exception $ex ) {

        }
// see if we have a session
        if ( isset( $session ) ) {
            // graph api request for user data
            $request = new FacebookRequest( $session, 'GET', '/me' );
            $response = $request->execute();

            Session::set('fb_token',"".$session->getAccessToken());
            Session::set('logout_link',"http://www.facebook.com/logout.php?next=http://localhost/src/app/loginf/logout/&access_token=".Session::get('fb_token'));
            $user_f = $response->getGraphObject()->asArray();

            Session::set('fb_ID',$user_f['id']);
            Session::set('lastname',$user_f['last_name']);
            Session::set('firstname',$user_f['first_name']);
            Session::set('email',$user_f['email']);
            $this->checkUser();
            exit;

        } else {
            $loginUrl = $helper->getLoginUrl();
            header("Location: ".$loginUrl);
        }
    }
    private function checkEmail($email){
        return $this->model->checkEmail($email);
    }
    public  function updateId($id){
        $this->model=$this->loadModel('regist');
        $this->model->updateFB(Session::get('fb_ID'),$id);
    }
    public function checkUser(){
        $check= $this->model->checkUserFB(Session::get('fb_ID'));

        if($check){
            $this->model=$this->loadModel("user");
            $id=$this->model->getIdFB(Session::get("fb_ID"));
            Session::set('id',$id);
            $isUnconf=$this->model->checkUnconfirmed($id);
            if($isUnconf){
                Session::set('status',"unconfirmed");

                header("Location:".URL."app/signin");;
                exit;
            }
            Session::set('status',"ok");
            header("Location:".URL."app/calendar");
            exit;
        }
        else {
            Session::set('status','regist');
            $this->model=$this->loadModel("check");
            if($this->model->checkEmail(Session::get('email'))){
                $this->model=$this->loadModel("regist");
                $this->model->updateFB(Session::get('fb_ID'),Session::get('email'));
                $this->model=$this->loadModel("user");
                $id=$this->model->getIdFB(Session::get("fb_ID"));
                Session::set('id',$id);
                Session::set('status','ok');
                header("Location:".URL."app/calendar");
                exit;
            }
            else{

                $this->view->renderHtml("regist/index");
            }
        }
    }
    public function logout(){
        setcookie('fbs_'.APP_ID_FB, '', time()-100, '/', $_SERVER["SERVER_NAME"]);
        unset($_SESSION['fb_'.APP_ID_FB.'_code']);
        unset($_SESSION['fb_'.APP_ID_FB.'_access_token']);
        unset($_SESSION['fb_'.APP_ID_FB.'_user_id']);
        unset($_SESSION['fb_'.APP_ID_FB.'_state']);
        Session::get('fb_ID',NULL);
        Session::get('fb_fullname', NULL);
        Session::get('fb_email',NULL);
        Session::get('status','not');
        session_destroy();
        header("Location:".URL."app/signin");
    }


}

?>