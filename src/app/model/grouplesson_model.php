<?php

class GrouplessonModel extends Model
{
    //видалити
    function addGroupToLesson($lessonId,$groupId)
    {
        try {
            $STH = $this->db->prepare("insert into group_lesson(group_id,lesson_id)values(:group_id,:lesson_id)");
            $STH->execute(array('group_id'=>$groupId, 'lesson_id'=>$lessonId));
            return "ok";

        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function addGroupsToLesson($lessonId, $groupId)
    {
        $STH = $this->db->prepare("insert into group_lesson(group_id,lesson_id)values(:group_id,:lesson_id)");
        for ($i = 0; $i < count($groupId); ++$i) {
            $STH->execute(array('group_id'=>$groupId[$i], 'lesson_id'=>$lessonId));
        }
        return "ok";
    }

    public function  deleteGroupsFromLesson($lessonId, $groupId)
    {
        $STH = $this->db->prepare("delete from group_lesson where group_id=:group_id AND lesson_id=:lesson_id");
        for ($i = 0; $i < count($groupId); ++$i) {
            $STH->execute(array('group_id'=>$groupId[$i], 'lesson_id'=>$lessonId));
        }
        return "ok";
    }
}


?>
