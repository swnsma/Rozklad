<?php

error_reporting(E_ALL);

require_once(__DIR__ . '/lib/mail/class.phpmailer.php');
require_once(__DIR__ . '/lib/mail/class.smtp.php');
require_once(__DIR__ . '/conf/conf.php');
require_once(__DIR__ . '/core/Mail.php');
require_once(__DIR__ . '/core/DataBase.php');
require_once 'core/PeddingOperation/PeddingOperation.php';

PeddingOperation::run();


?>