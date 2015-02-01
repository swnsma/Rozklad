<?php
require_once FILE .'conf/setup.php';
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

require_once (FILE.'module/app/controllers/signin.php');

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

    private $client;
    private $model;

    public function __construct() {
        parent::__construct();
        $this->model=$this->loadModel("check");
        FacebookSession::setDefaultApplication( '1536442079974268','1d75987fcb8f4d7abc1a34287f9601cf' );
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
            // When validation fails or other local issues
        }
// see if we have a session
        if ( isset( $session ) ) {
            // graph api request for user data
            $request = new FacebookRequest( $session, 'GET', '/me' );
            $response = $request->execute();
            // get response
            $_SESSION['fb_token']="".$session->getAccessToken();
//            echo $_SESSION['fb_token'];
            $graphObject = $response->getGraphObject();
            $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
            $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
            $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
            /* ---- Session Variables -----*/
            $_SESSION['fb_ID'] = $fbid;
            $_SESSION['fb_fullname'] = $fbfullname;
            $_SESSION['fb_email'] =  $femail;
            //checkuser($fuid,$ffname,$femail);
            $_SESSION['login']=1;
            header("Location:".URL."app/loginf/checkUser");
            exit;
        } else {
            $loginUrl = $helper->getLoginUrl();
            header("Location: ".$loginUrl);
        }
    }
    public function checkUser(){
//       echo $_SESSION['fb_ID'];
        $check= $this->model->checkUserFB($_SESSION['fb_ID']);

        if($check){
            header("Location:".URL."app/calendar");
            $_SESSION['regist']=1;
            exit;
        }
        else {
            $this->view->renderHtml("signin/index","regist");
        }
    }

    public function logout(){
        setcookie('fbs_'.$this->getAppId(), '', time()-100, '/', $_SERVER["SERVER_NAME"]);
        unset($_SESSION['fb_'.$this->getAppId().'_code']);
        unset($_SESSION['fb_'.$this->getAppId().'_access_token']);
        unset($_SESSION['fb_'.$this->getAppId().'_user_id']);
        unset($_SESSION['fb_'.$this->getAppId().'_state']);
        $_SESSION['fb_ID'] = NULL;
        $_SESSION['fb_fullname'] = NULL;
        $_SESSION['fb_email'] =  NULL;
        session_destroy();
        $_SESSION['login']=0;
        header("Location:".URL."app/signin");
    }
    public function getAppId(){
        return "1536442079974268";
    }
}

?>