<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 23:40
 */
class Request{
    private static $string = '';
    protected function __construct(){

    }
    protected function __clone(){

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