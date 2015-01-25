<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 25.01.2015
 * Time: 20:38
 */
class LessonModel extends Model {
    public function __construct() {
        parent::__construct();

    }
    public function addLesson($title, $start,$end) {
        try {
            $this->db->query("INSERT INTO lesson (title,start,end,date,topic,description) VALUES ('$title','$start','$end','NUL0L','NULL','NULL')");
            return $this->db->lastInsertId();

        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function getAllEvent(){
        try {
            $var =$this->db->query("SELECT * from 'lesson'")->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
}


?>