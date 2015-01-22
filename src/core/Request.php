<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 23:40
 */
class Request{
    protected static $string = '';
    private function __construct(){

    }
    private function __clone(){

    }
    public static function input($string){
        self::$string = self::$string . $string;
    }
    public static function output(){
        echo self::$string;
        self::$string='';
    }
}
?>