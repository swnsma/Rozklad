<?php

define ('DOCUMENT_ROOT', __DIR__);

class DataBase/* extends PDO*/ {
    private static $instance=null;
    private function __construct() {

    }
    private function  __clone(){

    }
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new PDO('sqlite:' . DOCUMENT_ROOT . '/../sql/data/rozklad.sqlite');
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}
?>