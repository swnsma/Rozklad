<?php

define('URL', 'http://' . $_SERVER['HTTP_HOST'] . '/src/');
define('DOC_ROOT', __DIR__ . '/../');
define('IMAGES_FOLDER', DOC_ROOT . 'public/users_files/images/');

//Google
define('CLIENT_ID_GM','1051661788648-aamr1ek05a6dq7dpt6fpm74s5gl9fqdl.apps.googleusercontent.com');
define('CLIENT_SECRET_GM','DQE7U9jZKTIfD6G76vEEj6QP');
define('REDIRECT_URI','http://localhost/src/app/login/token');//example:http://localhost/social/login.php?google,http://example/login.php?google
define('APPROVAL_PROMPT','auto');
define('ACCESS_TYPE','offline');

//Facebook
define('APP_ID_FB','1536442079974268');
define('APP_SECRET_FB','1d75987fcb8f4d7abc1a34287f9601cf');


//mail
define('MAIL_HOST', 'smtp.rambler.ru');
define('MAIL_PORT', 465);
define('MAIL_USERNAME', 'myrozklad@rambler.ru');
define('MAIL_PASSWORD', 'myrozklad');
define('MAIL_SET_FROM', 'myrozklad@rambler.ru');
define('MAIL_SET_FROM_NAME', 'My Rozklad');
define('MAIL_IS_SMTP_AUTH', true);

define('DEBUG', false);

?>