<?php
require_once DOC_ROOT . 'core/MagicObject.php';

abstract class Model extends Magic_Object {
    private $id = null;
    protected $db;
    /*protected static $instance = null;
    public static function getInstance(){
        if(!self::$instance){
            $c = get_called_class();
            self::$instance=new $c;
        }
        return self::$instance;
    }*/

    public function __construct() {
        $this->db = DataBase::getInstance()->DB();
    }
}

?>