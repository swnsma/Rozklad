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
            $this->db->query("DELETE FROM 'uncofirmed_users' WHERE 'id'=$id;");
            //return $this->db->lastInsertId(); //this left feom method 'addLesson'. Don't know what to place here instead

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