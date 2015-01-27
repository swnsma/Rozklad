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
            $this->db->query("INSERT INTO lesson (title,start,end) VALUES ('$title','$start','$end')");
            return $this->db->lastInsertId();

        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function getAllEvent(){
        try {
            $request = <<<HERE
select * from lesson
where `start` BETWEEN '2011-12-31 14:00:00' AND '2012-12-31 16:00:00'
HERE;

            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

}


?>