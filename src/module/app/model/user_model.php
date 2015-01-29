<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 25.01.2015
 * Time: 20:38
 */
class UserModel extends Model {
    public function __construct() {
        parent::__construct();

    }

    public function getInfo($fb_id){
        try {
            $request = <<<TANIA
select * from user
where fb_id=$fb_id
TANIA;

            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }



}


?>