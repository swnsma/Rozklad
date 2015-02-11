<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 25.01.2015
 * Time: 20:38
 */
class GrouplessonModel extends Model {
    public function __construct() {
        parent::__construct();
    }
    function addGroupToLesson($lessonId,$groupId){
        try {
            $STH = $this->db->prepare("insert into group_lesson(group_id,lesson_id)values(:group_id,:lesson_id)");
            $STH->execute(array('group_id'=>$groupId, 'lesson_id'=>$lessonId));
            return "ok";

        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
}


?>
