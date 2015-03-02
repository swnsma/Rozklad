<?php

class CheckModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkUserFB($id)
    {
        $user=$this->db->query("SELECT fb_id FROM user where fb_id='$id'")->fetchAll(PDO::FETCH_ASSOC);
        return count($user);
    }

    public function checkUserGM($id)
    {
        $user=$this->db->query("SELECT gm_id FROM user where gm_id='$id'")->fetchAll(PDO::FETCH_ASSOC);
        return count($user);
    }

    public function checkEmail($email)
    {
        $mas=$this->db->query("SELECT email FROM user where email='$email'")->fetchAll(PDO::FETCH_ASSOC);
        return count($mas)>0;
    }
}