<?php

define('URL', 'http://' . $_SERVER['HTTP_HOST'] . '/src/');
define('DOC_ROOT', __DIR__ . '/../');
define('IMAGES_FOLDER', DOC_ROOT . 'cdn/images');

define('BASE_URL', filter_var('http://localhost/src', FILTER_SANITIZE_URL));
//Google
define('CLIENT_ID_GM','955464663389-683pu19v53o6tg53h2hdt4s5ha6sqtu0.apps.googleusercontent.com');
define('CLIENT_SECRET_GM','dSc5Tm27rjaIfeslOZDMeZIW');
define('REDIRECT_URI','http://localhost/src/app/login/token');//example:http://localhost/social/login.php?google,http://example/login.php?google
define('APPROVAL_PROMPT','auto');
define('ACCESS_TYPE','offline');

//Facebook
define('APP_ID_FB','1536442079974268');
define('APP_SECRET_FB','1d75987fcb8f4d7abc1a34287f9601cf');
define('DEBUG', true);

?>