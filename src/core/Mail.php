<?php

require_once 'lib/mail/class.phpmailer.php';
require_once 'lib/mail/class.smtp.php';

class Mail
{
    private $mail;
    private static $instance = null;

    private function __construct()
    {
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
        $this->mail->SMTPSecure = MAIL_SMTP_SECURE;
        $this->mail->CharSet = 'UTF-8';
        $this->mail->IsHTML(true);
        $this->mail->setFrom(MAIL_SET_FROM, MAIL_SET_FROM_NAME);
    }

    public function send($address, $subject, $body)
    {
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

    public function addFile($file, $file_name)
    {
        $this->mail->AddAttachment($file, $file_name);
    }

    public function addFileToHtml($file, $file_name)
    {
        $this->mail->AddEmbeddedImage($file, $file_name);
    }

    public function getErrorInfo()
    {
        return $this->mail->ErrorInfo;
    }

    public function getTemplate($name, $data)
    {
	    if (isset($_SERVER['SERVER_NAME'])) {
       		$file = DOC_ROOT . 'public/mail_templates/' . $name . '.html';
	    } else {
		    $file = get_include_path() . 'public/mail_templates/' . $name . '.html';
	    }
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

    public function clear()
    {
        $this->mail->ClearAllRecipients();
        $this->mail->ClearAttachments();
        $this->mail->ClearCustomHeaders();
    }

    static public function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone() {}
}
