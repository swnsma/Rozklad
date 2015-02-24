<?php

class PeddingOperation {
    public static function run() {
        $fp = fsockopen($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "GET http://test-rozklad.z-tech.com.ua/mail.php HTTP/1.0\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            fclose($fp);
        }
    }
}
