<?php

define('URL', 'http://' . $_SERVER['HTTP_HOST'] . '/src/');
define('DOC_ROOT', __DIR__ . '/../');
define('IMAGES_FOLDER', DOC_ROOT . 'public/users_files/images/');
define('TASKS_FOLDER', DOC_ROOT . 'public/users_files/tasks/');
define('HOMEWORK_FOLDER', DOC_ROOT . 'public/users_files/homework/');

define('TIME_ZONE','Europe/Kiev');
//Google
define('CLIENT_ID_GM','955464663389-683pu19v53o6tg53h2hdt4s5ha6sqtu0.apps.googleusercontent.com');
define('CLIENT_SECRET_GM','dSc5Tm27rjaIfeslOZDMeZIW');
define('REDIRECT_URI','http://localhost/src/app/login/token');//example:http://localhost/social/login.php?google,http://example/login.php?google
define('APPROVAL_PROMPT','auto');
define('ACCESS_TYPE','offline');

//Facebook
//define('APP_ID_FB','384838578363750');
//define('APP_SECRET_FB','a9ebc86bba6ecd938e3fcd0c956c36a6');
define('APP_ID_FB','1536442079974268');
define('APP_SECRET_FB','1d75987fcb8f4d7abc1a34287f9601cf');


//mail
define('MAIL_HOST', 'smtp.rambler.ru');
define('MAIL_SMTP_SECURE', 'ssl');
define('MAIL_PORT', 465);
define('MAIL_USERNAME', 'myrozklad@rambler.ru');
define('MAIL_PASSWORD', 'myrozklad');
define('MAIL_SET_FROM', 'myrozklad@rambler.ru');
define('MAIL_SET_FROM_NAME', 'My Rozklad');
define('MAIL_IS_SMTP_AUTH', true);

define('DEBUG', true);

?>