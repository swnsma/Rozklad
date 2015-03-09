<?php
//require_once DOC_ROOT .'conf/setup.php';
require_once(DOC_ROOT . 'lib/facebook/HttpClients/FacebookHttpable.php');
require_once(DOC_ROOT . 'lib/facebook/Entities/AccessToken.php');
require_once(DOC_ROOT . 'lib/facebook/Entities/SignedRequest.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookSession.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookSignedRequestFromInputHelper.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookRedirectLoginHelper.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookRequest.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookResponse.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookSDKException.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookRequestException.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookOtherException.php');
require_once(DOC_ROOT . 'lib/facebook/FacebookAuthorizationException.php');
require_once(DOC_ROOT . 'lib/facebook/GraphObject.php');
require_once(DOC_ROOT . 'lib/facebook/GraphUser.php');
require_once(DOC_ROOT . 'lib/facebook/GraphSessionInfo.php');

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;


class Loginf extends Controller
{
    private $modelCheck;
    private $modelRegist;
    private $modelUser;

    public function __construct()
    {
        parent::__construct();
        Session::uns("gm_ID");
        $this->modelCheck = $this->loadModel('check');
        $this->modelUser = $this->loadModel('user');
        FacebookSession::setDefaultApplication(APP_ID_FB,APP_SECRET_FB);
    }

    public function login()
    {
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
            $request = new FacebookRequest( $session, 'GET', '/me?scope=email' );
            $response = $request->execute();

            Session::set('fb_token',"".$session->getAccessToken());
//            Session::set('logout_link',"http://www.facebook.com/logout.php?next=http://localhost/src/app/loginf/logout/&access_token=".Session::get('fb_token'));
            $user_f = $response->getGraphObject()->asArray();
            Session::set('fb_ID',$user_f['id']);
            Session::set('lastname',$user_f['last_name']);
            Session::set('firstname',$user_f['first_name']);

            if(isset($user_f['last_name'])) {
                Session::set('lastname', $user_f['last_name']);
            }
            if(isset($user_f['first_name'])) {
                Session::set('firstname', $user_f['first_name']);
            }
            if(isset($user_f['email'])){
                Session::set('email',$user_f['email']);
            }
            else{
                Session::set('email',NULL);
            }

            $this->checkUser();
            exit;

        } else {
            $loginUrl = $helper->getLoginUrl(array(
                'scope' => 'email'
            ));
            header("Location: ".$loginUrl);
        }
    }

    public  function updateId($id)
    {
        $this->modelRegist->updateFB(Session::get('fb_ID'),$id);
    }

    public function checkUser()
    {
        $check= $this->modelCheck->checkUserFB(Session::get('fb_ID'));
        if($check) {

            $id = $this->modelUser->getIdFB(Session::get("fb_ID"));
            Session::set('id',$id);

            Session::set('status',"ok");
            $link="app/calendar";
            if(Session::has('unusedLink')) {
                $link=Session::get('unusedLink');
                Session::uns('unusedLink');
            }
            $isUnconf=$this->modelCheck->checkUnconfirmed($id);
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
                    $this->modelRegist->updateFB(Session::get('fb_ID'), Session::get('email'));
                    $id = $this->modelUser->getIdFB(Session::get("fb_ID"));
                    Session::set('id', $id);
                    Session::set('status', 'ok');
                    $link = "app/calendar";
                    if(Session::has('unusedLink')){
                        $link = Session::get('unusedLink');
                        Session::uns('unusedLink');
                    }
                    $isUnconf = $this->modelUser->checkUnconfirmed($id);
                    if($isUnconf){
                        Session::set('status',"unconfirmed");
                    }
                    header("Location:" . URL .$link );
                    exit;
                }
                else{
                    header('Content-type: text/html; charset=utf-8');
                    header("Location:".URL."app/regist");
                    exit;
                }
            } else {
                header('Content-type: text/html; charset=utf-8');
                header("Location:" . URL . "app/regist");
                exit;
            }
        }
    }

    public function logout() {
        setcookie('fbs_'.APP_ID_FB, '', time()-100, '/', $_SERVER["SERVER_NAME"]);
        Session::uns('fb_'.APP_ID_FB.'_code');
        Session::uns('fb_'.APP_ID_FB.'_access_token');
        Session::uns('fb_'.APP_ID_FB.'_user_id');
        Session::uns('fb_'.APP_ID_FB.'_state');
        Session::set('fb_ID',NULL);
        Session::set('fb_fullname', NULL);
        Session::set('fb_email',NULL);
        Session::set('status','not');
        session_destroy();
        header("Location:".URL);
    }
}