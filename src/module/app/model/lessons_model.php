<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 25.01.2015
 * Time: 20:38
 */
class LessonsModel extends Model {
    public function __construct() {
        parent::__construct();

    }
    //повертає DateTime
    private function formatDate(){
        return "Y-m-d H:i:s";
    }
    private function realDate(){
        $var =date($this->formatDate());
        return new DateTime($var);
    }

    public function getOurEventTeacher($start,$end){
        try {
            $var = $this->db->prepare("
        SELECT lesson.id as id,
lesson.start as start,
lesson.end as end,
 lesson.title as title,
 lesson.teacher as teacher,
 user.name as teacher_name,
user.surname as teacher_surname,
 groups.name as group_name,
groups.color as group_color,
lesson.status as status,
(SELECT COUNT(*) FROM result WHERE lesson.id = result.lesson_id AND (result.grade = '' OR result.grade = null)) AS count_no_grade
FROM lesson
INNER JOIN user ON user.id = lesson.teacher
LEFT JOIN group_lesson ON group_lesson.lesson_id = lesson.id
LEFT JOIN groups ON group_lesson.group_id = groups.id
WHERE lesson.status = 1 AND (lesson.start BETWEEN :start AND :end)
");
            $var->execute(array(':start' => $start, ':end' => $end));
            $var1 = $var->fetchAll();
            return $var1;
        }
        catch(PDOException $e){
            echo $e->getMessage();
            return null;
        }

    }


}
