<?php

class PeddingOperation {
    public static function run() {
        $fp = fsockopen("localhost", 83, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "POST /src/PeddingOperation/commands.php HTTP/1.0\r\n";
            $out .= "Host: localhost:83\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            fclose($fp);
        }
    }
}