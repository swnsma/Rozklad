<?php


class DataBase {
    private static $instance = null;

    private function __construct() {
        try {
            self::$instance = new PDO('sqlite:' . INCLUDE_PATH . 'SQL/data/rozklad.sqlite');
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            exit;
        }
    }

    private function  __clone() {
    }

    public static function getInstance() {
        if(is_null(self::$instance)){
            self::$instance=new self();
        }
        return self::$instance;
    }

}

?>


?>