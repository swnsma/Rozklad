<?php

define('ROOT', 'src/');
define('URL', 'http://' . $_SERVER['HTTP_HOST'] . '/' . ROOT);
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

define('DEBUG', true);
//
//define('DISQUS_SECRET_KEY', 'LmyNANslLx7Kox5mIfMOQiDo91uAmyYeSVgZ7owoAH9UaQhBJIBlmzdYsNaMEJLY');
//define('DISQUS_PUBLIC_KEY', 'gSu6P6ZaSFOPHMJ7HDaRprnE1qC86XuFe8xU6qpwxf58pmLS4O83kfv8Ti5OiXQl');
//
define('DISQUS_SECRET_KEY', 'OWXEOra8Y8VKldsvJuGqW1XaueQy5LKXG3G6bRO79XO29lQnDdstwUhSnYn3tHdR');
define('DISQUS_PUBLIC_KEY', 'KDCo6JfYbQFJv9Dzk8c79JkR1KyhTfStAkhOSZMCfBEXu2n2h2zKOjQ10n4G3Hqc');

define ('GREEN_ELEPHANT', true);