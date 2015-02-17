<?php

phpinfo();

error_reporting(E_ALL);

require_once(__DIR__ . '/lib/mail/class.phpmailer.php');
require_once(__DIR__ . '/lib/mail/class.smtp.php');
require_once(__DIR__ . '/conf/conf.php');

define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'myrozklad@gmail.com');
define('MAIL_PASSWORD', 'dfygjvgrd54e67rtfgdufhg');
define('MAIL_SET_FROM', 'myrozklad@gmail.com');
define('MAIL_SET_FROM_NAME', 'My Rozklad');

/*
$mail = new phpmailer();
$mail->IsSendmail();
$mail->Mailer = "smtp";
$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->Host = 'aspmx.l.google.com';
$mail->Port =  25;
$mail->SMTPAuth = false;
//$mail->Username = 'myrozklad@gmail.com';
//$mail->Password = 'dfygjvgrd54e67rtfgdufhg';
$mail->SMTPSecure = 'tls';
$address = "vova.konstanchuk@gmail.com";
$mail->Body = 'sadds';
$mail->setFrom('myrozklad@gmail.com', 'My Rozklad');
$mail->AddAddress($address);
if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}*/

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
        $this->mail->SMTPSecure = 'tls';
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