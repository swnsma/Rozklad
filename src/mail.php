<?php

error_reporting(E_ALL);

require_once(__DIR__ . '/lib/mail/class.phpmailer.php');
require_once(__DIR__ . '/lib/mail/class.smtp.php');
require_once(__DIR__ . '/conf/conf.php');
require_once(__DIR__ . '/core/Mail.php');
require_once(__DIR__ . '/core/DataBase.php');

/*
    Приклади
*/

// повідлення для вчителя
function example1() {
    $m = Mail::getInstance();
    $template = $m->getTemplate('letterToTeacher2', array(
        'userName' => 'User Name',
        'mail_background' => 'mail_background',
        'mail_sep' => 'mail_sep',
        'url' => 'http://google.com'
    ));
    if (is_null($template)) {
        echo 'template is not exists';
    } else {
        //$m->addFile(DOC_ROOT . 'public/img/bg.jpg', 'images.jpg');
        $m->addFileToHtml(DOC_ROOT . 'public/img/mail_background.png', 'mail_background');
        $m->addFileToHtml(DOC_ROOT . 'public/img/mail_sep.png', 'mail_sep');
        if ($m->send(array(
            'myrozklad@mail.ru'
        ), 'subject', $template)) {
            echo 'true';
        } else {
            echo 'false';
            echo $m->getErrorInfo();
        }
    }
}

// повідомлення для адміна
function example2() {
    $m = Mail::getInstance();
    $template = $m->getTemplate('letterToAdmin2', array(
        'userName' => 'User Name',
        'phone' => '4324323',
        'email' => 'sdfef34fsfef3@gmail.com',
        'mail_background' => 'mail_background',
        'url' => 'http://google.com'
    ));
    if (is_null($template)) {
        echo 'template is not exists';
    } else {
        $m->addFileToHtml(DOC_ROOT . 'public/img/mail_background2.jpg', 'mail_background');
        if ($m->send(array(
            'myrozklad@mail.ru'
        ), 'subject', $template)) {
            echo 'true';
        } else {
            echo 'false';
            echo $m->getErrorInfo();
        }
    }
}

// виклик
/*
example1();
Mail::getInstance()->clear();
example2();
*/

require_once __DIR__  . '/core/MailerOfLetter.php';

$m = MailerOfLetter::getInstance();
$m->sendInvitationToLesson();

?>