<?php

error_reporting(E_ALL);

require_once(__DIR__ . '/lib/mail/class.phpmailer.php');
require_once(__DIR__ . '/lib/mail/class.smtp.php');
require_once(__DIR__ . '/conf/conf.php');
require_once(__DIR__ . '/core/Mail.php');

$m = Mail::getInstance();
if ($m->send(array('swnsma@gmail.com'), 'subject', '123434556')) {
    echo 'true';
} else {
    echo 'false';
    echo $m->getErrorInfo();
}

?>