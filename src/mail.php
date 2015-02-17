<?php

error_reporting(E_ALL);

require_once(__DIR__ . '/lib/mail/class.phpmailer.php');
require_once(__DIR__ . '/lib/mail/class.smtp.php');
require_once(__DIR__ . '/conf/conf.php');

define('MAIL_HOST', 'smtp.rambler.ru');
define('MAIL_PORT', 465);
define('MAIL_USERNAME', 'myrozklad@rambler.ru');
define('MAIL_PASSWORD', 'myrozklad');
define('MAIL_SET_FROM', 'myrozklad@rambler.ru');
define('MAIL_SET_FROM_NAME', 'My Rozklad');

class Mail {
    private $mail;

    public function __construct() {
        $this->mail = new phpmailer();
        $this->mail->IsSendmail();
        $this->mail->Mailer = 'smtp';
        $this->mail->IsSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Host = MAIL_HOST;
        $this->mail->Port =  MAIL_PORT;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = MAIL_USERNAME;
        $this->mail->Password = MAIL_PASSWORD;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->setFrom(MAIL_SET_FROM, MAIL_SET_FROM_NAME);
    }

    public function send($address, $subject, $body) {
        foreach($address as $mail) {
            $this->mail->AddAddress($mail);
        }
        $this->mail->Body = $body;
        $this->mail->Subject = $subject;
        if($this->mail->Send()) {
            return true;
        } else {
            print $this->mail->ErrorInfo;
            return false;
        }
    }
}


$m = new Mail();
if ($m->send(array('swnsma@gmail.com'), 'subject', 'body')) {
    echo 'true';
} else {
    echo 'false';
}

?>