<?php
require_once( FILE.'facebook/HttpClients/FacebookHttpable.php' );
require_once( FILE.'facebook/HttpClients/FacebookCurl.php' );
require_once(FILE.'/facebook/HttpClients/FacebookCurlHttpClient.php' );
// added in v4.0.0
require_once(FILE.'/facebook/FacebookSession.php' );
require_once( FILE.'facebook/FacebookRedirectLoginHelper.php' );
require_once( FILE.'facebook/FacebookRequest.php' );
require_once(FILE.'/facebook/FacebookResponse.php' );
require_once(FILE.'facebook/FacebookSDKException.php' );
require_once( FILE.'/facebook/FacebookRequestException.php' );
require_once( FILE.'/facebook/FacebookOtherException.php' );
require_once(FILE.'/facebook/FacebookAuthorizationException.php' );
require_once(FILE.'facebook/GraphObject.php' );
require_once( FILE.'facebook/GraphSessionInfo.php' );
// added in v4.0.5

use Facebook\HttpClients;
// added in v4.0.0
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
class Check extends Controller
{

    public $fbuser;

    public function index(){
        $id = '399004123614787'; // please use yours
        $secret = '45bd07c80d6a7e6bbd268259ab109f77'; // please use yours
        FacebookSession::setDefaultApplication($id, $secret);

        $helper = new \Facebook\FacebookJavaScriptLoginHelper();
        // see if a existing session exists
        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            // create new session from saved access_token
            $session = new FacebookSession($_SESSION['fb_token']);
            // validate the access_token to make sure it's still valid
            try {
                if (!$session->validate()) {
                    $session = null;
                }
            } catch (Exception $e) {
                // catch any exceptions
                $session = null;
            }
        } else {
            // no session exists
            try {
                $session = $helper->getSession();
            } catch (FacebookRequestException $ex) {
                // When Facebook returns an error
            }
        }
        // see if we have a session
        if (isset($session)) {
            // save the session
            $_SESSION['fb_token'] = $session->getToken();
            // create a session using saved token or the new one we generated at login
            $session = new FacebookSession($session->getToken());
            // graph api request for user data
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
            echo "TRUE";
        }
        else{
            echo "FALSE";
        }
    }
}
?>