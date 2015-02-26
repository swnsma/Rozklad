<?php

set_include_path('/var/www/vhosts/rozklad_test/public_html/');

require_once 'conf/conf.php';
require_once 'core/DataBase.php';
require_once 'core/MailerOfLetter.php';
//require_once 'module/app/controllers/sendermail.php';

$m = MailerOfLetter::getInstance();
$m->sendInvitationToLesson();

?>