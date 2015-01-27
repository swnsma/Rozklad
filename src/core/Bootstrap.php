<?php
require_once( URL.'facebook/Facebook/HttpClients/Httpable.php' );
require_once( URL.'facebook/FacebookCurl.php' );
require_once(URL.'/facebook/FacebookCurlHttpClient.php' );
// added in v4.0.0
require_once(URL.'/facebook/FacebookSession.php' );
require_once( URL.'facebook/FacebookRedirectLoginHelper.php' );
require_once( URL.'facebook/FacebookRequest.php' );
require_once(URL.'/src/facebook/FacebookResponse.php' );
require_once(URL.'/src/facebook/FacebookSDKException.php' );
require_once( URL.'/facebook/FacebookRequestException.php' );
require_once( URL.'/src/facebook/FacebookOtherException.php' );
require_once(URL.'/src/facebook/FacebookAuthorizationException.php' );
require_once(URL.'facebook/GraphObject.php' );
require_once( URL.'facebook/GraphSessionInfo.php' );
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
class Bootstrap {
    function __construct() {
        $request = Request::getInstance();
        $controller = $request->getController();
        $module = $request->getModule();
        $file = FILE . 'module/' . $module . '/controllers/' . $controller . '.php';
        if (file_exists($file)) {
            require_once $file;
            $c = new $controller;
        } else {
            require_once FILE . 'module/app/controllers/error.php';
            $c = new Error();
        }
        $c->run($request->getAction());

//
    }
}

?>