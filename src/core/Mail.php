<?php

require_once DOC_ROOT . '/lib/mail/class.phpmailer.php';
require_once DOC_ROOT . '/lib/mail/class.smtp.php';

class Mail {
    private $mail;
    private static $instance = null;

    private function __construct() {
        $this->mail = new phpmailer();
        $this->mail->IsSendmail();
        $this->mail->Mailer = 'smtp';
        $this->mail->IsSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Host = MAIL_HOST;
        $this->mail->Port =  MAIL_PORT;
        $this->mail->SMTPAuth = MAIL_IS_SMTP_AUTH;
        $this->mail->Username = MAIL_USERNAME;
        $this->mail->Password = MAIL_PASSWORD;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->CharSet = 'UTF-8';
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
            return false;
        }
    }

    public function getErrorInfo() {
        return $this->mail->ErrorInfo;
    }

    public function getTemplate($name, $data) {
        $file = DOC_ROOT . 'public/mail_templates/' . $name . '.html';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $keys = array_keys($data);
            foreach($keys as $key) {
                $content = str_replace('{' . $key . '}', $data[$key], $content);
            }
            return $content;
        }
        return null;
    }

    static public function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}