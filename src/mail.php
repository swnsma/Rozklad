<?php

error_reporting(E_ALL);

require_once(__DIR__ . '/lib/mail/class.phpmailer.php');
require_once(__DIR__ . '/lib/mail/class.smtp.php');
require_once(__DIR__ . '/conf/conf.php');
require_once(__DIR__ . '/core/Mail.php');
$date = date('n/d/Y h:i', time());
$m = Mail::getInstance();
$template = $m->getTemplate('letterToTeacher', array( "userName"=>"ИванБобров", "date"=>$date,
    'link' => 'vk.com'
));
if (is_null($template)) {
    echo 'template is not exists';
} else {
    $m->addFile(DOC_ROOT . 'public/img/bg.jpg', 'images.jpg');
    if ($m->send(array('swnsma@gmail.com'), 'subject', $template)) {
        echo 'true';
    } else {
        echo 'false';
        echo $m->getErrorInfo();
    }

}
?>