<?php

set_include_path('/var/www/vhosts/rozklad_test/public_html/');

require_once 'conf/mail_conf.php';
require_once 'core/cron/database.php';
require_once 'core/cron/MailerOfLetter.php';

$m = MailerOfLetter::getInstance();
$m->sendInvitationToLesson();

?>