<?php

error_reporting(E_ALL);

require_once(__DIR__ . '/lib/mail/class.phpmailer.php');
require_once(__DIR__ . '/lib/mail/class.smtp.php');
require_once(__DIR__ . '/conf/conf.php');
require_once(__DIR__ . '/core/Mail.php');

$m = Mail::getInstance();
$template = $m->getTemplate('example', array(
    'link' => 'google.com'
));
if (is_null($template)) {
    echo 'template is not exists';
} else {
    if ($m->send(array('swnsma@gmail.com'), 'subject', $template)) {
        echo 'true';
    } else {
        echo 'false';
        echo $m->getErrorInfo();
    }

}
?>