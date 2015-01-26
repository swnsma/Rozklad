<?php
require_once DOCUMENT_ROOT . 'core/magic_object.php';
require_once DOCUMENT_ROOT . 'core/Database.php';
abstract class Model extends MagicObject {
    private $id = null;
    protected $db;

    function __construct() {
        $this->db = DataBase::getInstance()->DB();
    }

    protected function check_user() {
        $request = Request::getInstance();
        $id = $request::getCookie('id');
        $key = $request::getCookie('key');
        //додати перевірки
        try { //переписати
            $r = $this->db->query("SELECT id FROM user WHERE id = $id and key = '$key'")->fetchAll();
            if (count($r) > 0) {
                $this->id = $id;
                return true;
            }
        } catch(PDOException $e) {}
        return false;
    }

    protected function getId() {
        return $this->id;
    }
}

?>