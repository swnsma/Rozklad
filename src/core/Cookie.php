<?php

class Cookie {
    public static function get($key) {
        return $_COOKIE[$key];
    }

    public static function set($key, $value) {
        SetCookie($key, $value, time()+3600, '/');
    }
}