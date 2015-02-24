<?php

class PeddingOperation {
    public static function run() {
        $fp = fsockopen($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT'], $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "POST /core/PeddingOperation/commands.php HTTP/1.0\r\n";
            $out .= "Host: rozklad.z-tech.com.ua\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            /*while (!feof($fp)) {
                echo fgets($fp, 128);
            }*/
            fclose($fp);
        }
    }
}