<?php

class DataBase{
    private static $pdo=null;
    private static $instance=null;

     private function __construct() {
        try {
            self::$pdo = new PDO('sqlite:' . DOC_ROOT . 'SQL/data/rozklad.sqlite');
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            echo $e;
        }
    }

    private function  __clone(){

    }

    public static function getInstance(){
        if(!self::$instance){
            self::$instance=new DataBase();
        }
        return self::$instance;
    }

    public function DB(){
        return self::$pdo;
    }
}
?>
