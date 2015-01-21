<?php

require_once 'conf/conf.php';

class DataBase extends PDO {
    function __construct() {
        //parent::__construct('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        parent::__construct('sqlite:app_date/rozklad-db.sql');
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}

?>