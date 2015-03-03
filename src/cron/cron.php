<?php

set_include_path('./../');

require_once 'conf/mail_conf.php';
require_once 'cron/MailerOfLetter.php';
require_once 'cron/RealDelLesson.php';

$m = MailerOfLetter::getInstance();
$m->sendInvitationToLesson();

$l = RealDelLesson::getInstance();
$l->run();

?>