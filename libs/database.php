<?php

define ('DOCUMENT_ROOT', __DIR__);

class DataBase extends PDO {
    function __construct() {
        parent::__construct('sqlite:' . DOCUMENT_ROOT . '/../app_date/db.sqlite');
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}

?>