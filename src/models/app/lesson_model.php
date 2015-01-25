<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 25.01.2015
 * Time: 20:38
 */
class LessonModel extends Model {
    function __construct() {
        parent::__construct();

    }
    public function addLesson($title, $start,$end) {
        try {
            $this->db->query("INSERT INTO lesson (title,start,end,date,topic,description) VALUES ($title,$start,$end,NULL,NULL,NULL)");
            return $this->db->lastInsertId();
        } catch(PDOException $e) {
            return null;
        }
    }
}


?>