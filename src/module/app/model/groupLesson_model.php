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
            $request = <<<BORIA
            insert into group_lesson(group_id,lesson_id)values('$groupId','$lessonId')
BORIA;

            $this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return "ok";

        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
}


?>