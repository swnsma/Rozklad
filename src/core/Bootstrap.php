<?php
require_once( DOCUMENT_ROOT .'facebook/HttpClients/FacebookHttpable.php' );
require_once(DOCUMENT_ROOT . 'facebook/HttpClients/FacebookCurl.php' );
require_once( DOCUMENT_ROOT .'facebook/HttpClients/FacebookCurlHttpClient.php' );

// added in v4.0.0
require_once( DOCUMENT_ROOT .'facebook/FacebookSession.php' );
require_once(DOCUMENT_ROOT . 'facebook/FacebookRedirectLoginHelper.php' );
require_once( DOCUMENT_ROOT .'facebook/FacebookRequest.php' );
require_once( DOCUMENT_ROOT .'facebook/FacebookResponse.php' );
require_once( DOCUMENT_ROOT .'facebook/FacebookSDKException.php' );
require_once(DOCUMENT_ROOT . 'facebook/FacebookRequestException.php' );
require_once( DOCUMENT_ROOT .'facebook/FacebookOtherException.php' );
require_once( DOCUMENT_ROOT .'facebook/FacebookAuthorizationException.php' );
require_once( DOCUMENT_ROOT .'facebook/GraphObject.php' );
require_once(DOCUMENT_ROOT . 'facebook/GraphSessionInfo.php' );

// added in v4.0.5
use Facebook\FacebookHttpable;
use Facebook\FacebookCurl;
use Facebook\FacebookCurlHttpClient;
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

require_once DOCUMENT_ROOT . 'core/magic_object.php';
require_once DOCUMENT_ROOT . 'inc/facebook.php'; //include fb sdk
class Bootstrap extends  MagicObject{
    function __construct() {
        $request = Request::getInstance();
        $controller = $request->getController();
        $module = $request->getModule();
        $file = DOCUMENT_ROOT . 'controllers/' . $module . '/' . $controller . '.php';
        if (file_exists($file)) {
            require_once $file;
            $c = new $controller;
        } else {
            require_once DOCUMENT_ROOT . 'controllers/app/error.php';
            $c = new Error();
        }
        $c->run($request->getAction());

//
    }
}

?>