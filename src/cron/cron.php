<?php

set_include_path('./../');

require_once 'conf/mail_conf.php';
require_once 'core/cron/MailerOfLetter.php';

$m = MailerOfLetter::getInstance();
$m->sendInvitationToLesson();

?>