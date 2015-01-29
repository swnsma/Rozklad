<?php
require_once FILE . 'core/MagicObject.php';
require_once FILE . 'core/Database.php';
abstract class Model extends Magic_Object {
    private $id = null;
    protected $db;

    function __construct() {
        $this->db = DataBase::getInstance()->DB();
    }
}

?>