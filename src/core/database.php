<?php

class DataBase extends PDO {
    function __construct() {
        parent::__construct('sqlite:' . __DIR__ . '/../SQL/data/rozklad.sqlite');
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}

?>