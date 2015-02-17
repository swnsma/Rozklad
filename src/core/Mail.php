<?php

require_once DOC_ROOT . '/lib/mail/class.phpmailer.php';
require_once DOC_ROOT . '/lib/mail/class.smtp.php';

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