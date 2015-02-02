<?php

error_reporting(E_ALL);

require_once(__DIR__ . '/lib/mail/class.phpmailer.php');
require_once(__DIR__ . '/lib/mail/class.smtp.php');

$mail = new phpmailer();
$mail->IsSendmail();
$mail->Mailer = "smtp";
$mail->IsSMTP();
$mail->SMTPDebug = 0;
$mail->Host = 'smtp.postmarkapp.com';
$mail->Port = 2525;
$mail->SMTPAuth = true;
$mail->Username = '2ca26439-a4fd-4385-a235-54840206e877';
$mail->Password = '2ca26439-a4fd-4385-a235-54840206e877';
$mail->SMTPSecure = 'tls';
$address = "rozklad@rdam.zz.mu";
$mail->Body = 'sadds';
$mail->setFrom('rozklad@rdam.zz.mu', 'myrozklad');
$mail->AddAddress($address);
if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
