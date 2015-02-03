<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.01.2015
 * Time: 22:34
 */
class RegistModel extends Model{
    public function __construct() {
        parent::__construct();
    }
    public function addUser($name,$surname,$phone,$role,$fb_id,$gm_id)
    {
        try {
            $this->db->query("INSERT INTO user (name,surname,phone,role_id,fb_id,gm_id) VALUES ('$name','$surname','$phone','$role','$fb_id','$gm_id')");
//            if($role===1){
//                $id= $this->db->query("SELECT id FROM user WHERE fb_id='$fb_id' AND gm_id='$gm_id'");
//                $this->db->query("INSERT INTO unconfirmed_user (id) VALUES ('$id')");
//            }
            $_SESSION['status']="signin";
            return 1;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function checkUserFB($id){
        try {
            $arr= $this->db->query("SELECT fb_id FROM user WHERE fb_id=$id")->fetchAll();
            return count($arr);
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function checkUserGM($id){
        try {
            $arr = $this->db->query("SELECT gm_id FROM user WHERE gm_id=$id")->fetchAll();
            return count($arr);
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function updateGM($gm_id,$email){
        try {
            $this->db->query("UPDATE user SET gm_id='$gm_id' where email='$email'")->fetchAll();
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function updateFB($fb_id,$email){
        try {
            $this->db->query("UPDATE user SET fb_id='$fb_id'  where id='$email'")->fetchAll();
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }
}