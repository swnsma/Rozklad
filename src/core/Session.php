<?php
class Session {
    public static function init($time,$ses) {

        session_set_cookie_params($time);
        session_name($ses);
        session_start();

//        if (isset($_COOKIE[$ses]))
//            setcookie($ses, $_COOKIE[$ses], time() + $time, "/");
    }

    public static function has($key) {
        return isset($_SESSION[$key]) && !empty($_SESSION[$key]);
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        if(isset($_SESSION[$key])){
        return $_SESSION[$key];
        }
        else null;
    }
    public static function uns($key){
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        session_destroy();
    }
}

?>