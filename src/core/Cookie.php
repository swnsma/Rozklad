<?php
require_once DOCUMENT_ROOT . 'core/magic_object.php';
class Cookie extends MagicObject {
    public static function getCookie($key) {
        return $_COOKIE[$key];
    }

    public static function setCookie($key, $value) {
        SetCookie($key, $value, time()+3600, '/');
    }
}

?>