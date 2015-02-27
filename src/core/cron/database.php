<?php

class DataBase {
    private static $instance = null, $db;

    private function __construct() {
        try {
            self::$db = new PDO('sqlite:' . get_include_path() . 'SQL/data/rozklad.sqlite');
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
		echo $e->getMessage();
            exit;
        }
    }

    private function  __clone() {
    }

    public static function getInstance() {
        if(is_null(self::$instance)){
            self::$instance=new self();
        }
        return self::$db;
    }

}

?>
