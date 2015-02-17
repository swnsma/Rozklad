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

    public function getAllGroupsForThisLesson($id)
    {

        try {
            $request = <<<BORIA
  SELECT
b.id,
b.name,
b.description,
b.teacher_id,
b.color
FROM groups AS b
JOIN group_lesson AS ba ON b.id = ba.group_id
JOIN lesson AS a ON a.id = ba.lesson_id
WHERE ba.lesson_id = $id AND  b.archived=0
BORIA;

            $var = $this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    //prepare
    public function  getOurLessonForThisIdTeacherCurrent($userinfo,$start,$end){
        try {
            $id = $userinfo['id'];

            $res = "select l.id,
            l.title, l.date,l.description, l.start, l.end,l.status,l.teacher,u.name,u.surname
              from lesson as l
              INNER JOIN  user as u ON
              (u.id = l.teacher) AND u.id='$id'
            WHERE  (l.start BETWEEN '$start' AND '$end') AND l.status='1'";
            $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
            for($i=0;$i<count($var);$i++){
                $var[$i]['group']=$this->getAllGroupsForThisLesson($var[$i]["id"]);
            }
            $result = array_unique($var,SORT_REGULAR);
            sort($result);
//            print_r($result);
            return $result;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    //prepare
    public function  getOurLessonForThisIdTeacherNoCurrent($userinfo,$start,$end){
        try {

//            print_r($userinfo);
            $id = $userinfo['id'];

            $res = "select l.id,
            l.title, l.date,l.description, l.start, l.end,l.status,l.teacher,u.name,u.surname
              from lesson as l
              INNER JOIN  user as u ON
              (u.id = l.teacher) AND u.id!='$id'
            WHERE  (l.start BETWEEN '$start' AND '$end') AND l.status='1'";
            $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);

            for($i=0;$i<count($var);$i++){
                $var[$i]['group']=$this->getAllGroupsForThisLesson($var[$i]["id"]);
            }
            $result = array_unique($var,SORT_REGULAR);
            sort($result);
            return $result;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    //prepare
    public function  getOurLessonForThisIdStudent($userinfo,$start,$end){
        try {

//            print_r($userinfo);
            $id = $userinfo['id'];
            $res = "select l.id,
            l.title, l.date,l.description, l.start, l.end,l.status,l.teacher,u.name,u.surname
            from 'student_group'as st_g
            INNER JOIN 'groups' as g ON
            g.id=st_g.group_id
            INNER JOIN  'group_lesson' as  gr ON
            st_g.group_id=gr.group_id
            INNER JOIN 'lesson' as l ON
            l.id=gr.lesson_id
            INNER JOIN 'user' as u ON
            u.id=l.teacher
            WHERE (st_g.student_id='$id')
            AND (l.start BETWEEN '$start' AND '$end') AND l.status='1' AND g.archived=0";
            $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
            for($i=0;$i<count($var);$i++){
                $var[$i]['group']=$this->getAllGroupsForThisLesson($var[$i]["id"]);
            }
            $result = array_unique($var,SORT_REGULAR);
            sort($result);
//            print_r($result);
            return $result;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function eventDrop($id, $start, $end){
        try {
            $date = $this->realDate()->format($this->formatDate());
            $SHT=$this->db->prepare("UPDATE lesson SET start=:start,end=:end,update_date=:update_date WHERE id=:id");
            $SHT->execute(array( 'start'=>$start, 'end'=>$end, 'update_date'=>$date,  'id'=>$id));
            return true;

        } catch(PDOException $e) {
            echo $e;
            return false;
        }
    }
}
