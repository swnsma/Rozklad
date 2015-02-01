<?php

require_once(__DIR__ . '/src/lib/mail/class.phpmailer.php');
//require_once(__DIR__ . '/src/lib/mail/PHPMailerAutoload.php');
require_once(__DIR__ . '/src/lib/mail/class.smtp.php');
//require_once(__DIR__ . '/src/lib/mail/class.pop3.php'); // required for POP before SMTP

$mail = new phpmailer();
$mail->IsSendmail();
$mail->Mailer = "smtp";
$mail->IsSMTP();
$mail->SMTPDebug = 1;
$mail->Host = 'mx1.hostinger.com.ua';
$mail->Port = 2525;
$mail->SMTPAuth = true;
$mail->Username = 'rozklad@rdam.zz.mu';
$mail->Password = 'iQiXYdEA3omRu';
$mail->SMTPSecure = '';
$address = "rozklad@list.ru";
$mail->Body = 'sadds';
$mail->AddAddress($address);
if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
