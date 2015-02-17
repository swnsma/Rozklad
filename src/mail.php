<?php

error_reporting(E_ALL);

require_once(__DIR__ . '/lib/mail/class.phpmailer.php');
require_once(__DIR__ . '/lib/mail/class.smtp.php');
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
        $this->mail->Mailer = "smtp";
        $this->mail->IsSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Host = 'aspmx.l.google.com';
        $this->mail->Port =  25;
        $this->mail->SMTPAuth = false;
        $this->mail->setFrom('myrozklad@gmail.com', 'My Rozklad');
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