<?php
require_once DOC_ROOT . 'core/MagicObject.php';

abstract class Model extends Magic_Object {
    private $id = null;
    protected $db;

    public function __construct() {
        $this->db = DataBase::getInstance()->DB();
    }
}

?>