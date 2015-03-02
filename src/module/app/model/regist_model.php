<?php

class RegistModel extends Model{
    public function __construct()
    {
        parent::__construct();
    }

    public function addUser($name,$surname,$phone,$role,$fb_id,$gm_id,$email)
    {
        try {
            $stm=$this->db->prepare("INSERT INTO user (name,surname,phone,role_id,fb_id,gm_id,email) VALUES (:name,:surname,:phone,:role_id,:fb_id,:gm_id,:email)");

            $stm->bindParam(":name",$name);
            $stm->bindParam(":surname",$surname);
            $stm->bindParam(":phone",$phone);
            $stm->bindParam(":role_id",$role);
            $stm->bindParam(":fb_id",$fb_id);
            $stm->bindParam(":gm_id",$gm_id);
            $stm->bindParam(":email",$email);
            $stm->execute();
            $id=$this->db->query("select id from user where email='$email'")->fetchAll();
            $id=$id[0]['id'];
            if($role=='1')
                $this->addTeacher($id);
            return 1;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function checkUserFB($id)
    {
        try {
            $arr= $this->db->query("SELECT fb_id FROM user WHERE fb_id='$id'")->fetchAll();
            return count($arr);
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function addTeacher($id)
    {
        try {
            $this->db->query("INSERT INTO unconfirmed_user (id) VALUES ('$id')");
            return 1;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }

    }

    public function checkUserGM($id)
    {
        try {
            $arr = $this->db->query("SELECT gm_id FROM user WHERE gm_id='$id'")->fetchAll();
            return count($arr);
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function updateGM($gm_id,$email)
    {
        try {
            $this->db->query("UPDATE user SET gm_id='$gm_id' where email='$email'")->fetchAll();
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function updateFB($fb_id,$email)
    {
        try {
            $this->db->query("UPDATE user SET fb_id='$fb_id'  where email='$email'")->fetchAll();
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function getRoles()
    {
        try {
            $arr=$this->db->query("select title as roleName from role")->fetchAll();
            return $arr;
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }
}