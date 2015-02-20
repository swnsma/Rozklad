<?php

require_once __DIR__ . '/../../conf/conf.php';
require_once DOC_ROOT . 'core/DataBase.php';
require_once DOC_ROOT . '/core/MailerOfLetter.php';
require_once DOC_ROOT.'/module/app/controllers/sendermail.php';

$m = MailerOfLetter::getInstance();
$m->sendInvitationToLesson();