<?php
define('BASE_URL', filter_var('http://localhost/src', FILTER_SANITIZE_URL));
define('CLIENT_ID','955464663389-683pu19v53o6tg53h2hdt4s5ha6sqtu0.apps.googleusercontent.com');
define('CLIENT_SECRET','dSc5Tm27rjaIfeslOZDMeZIW');
define('REDIRECT_URI','http://localhost/src/app/login/token');//example:http://localhost/social/login.php?google,http://example/login.php?google
define('APPROVAL_PROMPT','auto');
define('ACCESS_TYPE','offline');

define('APP_ID','FACEBOOK APP ID');
define('APP_SECRET','FACEBOOK APP SECRET');

?>