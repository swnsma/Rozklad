<?php

set_include_path('./../');

require_once 'conf/mail_conf.php';
require_once 'cron/MailerOfLetter.php';
require_once 'module/app/model/lesson_model.php';

$m = MailerOfLetter::getInstance();
$m->sendInvitationToLesson();

LessonModel::realDeletedLesson();

?>