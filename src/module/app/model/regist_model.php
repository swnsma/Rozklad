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
    public function index($name,$surname,$phone,$fb_id)
    {
        try {
            $this->db->query("INSERT INTO user (name,surname,phone,fb_id) VALUES ('$name','$surname','$phone','$fb_id')");
        } catch (PDOException $e) {
            echo $e;
            return null;
        }
    }
}