<?php

class DataBase {
    private static $instance=null;
    private function __construct() {

    }
    private function  __clone(){

    }
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new PDO('sqlite:' . __DIR__ . '/../sql/data/rozklad.sqlite');
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}
?>