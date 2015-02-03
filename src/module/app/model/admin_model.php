<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 27.01.2015
 * Time: 17:00
 */
class AdminModel extends Model {
    public function __construct() {
        parent::__construct();

    }

    public function confirmUser($id) {
        try {
            $this->db->query("DELETE FROM unconfirmed_user WHERE id=$id;");
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function unConfirmUser($id) {
        try {
            $this->db->query("INSERT INTO unconfirmed_user (id) VALUES ($id)");
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function getUnconfirmedUsers(){
        try {
            $var =$this->db->query("SELECT * FROM user INNER JOIN unconfirmed_user ON unconfirmed_user.id=user.id; ")->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
}


?>