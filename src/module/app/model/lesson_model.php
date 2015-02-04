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
    //повертає DateTime
    private function formatDate(){
        return "Y-m-d H:i:s";
    }
    private function realDate(){
        $var =date($this->formatDate());
        return new DateTime($var);
    }

    //повертає з бази всі записи з проміжку часу [$start, $end]
    private function getEventsInterval($start,$end,$fieldTime){
        try {
//            $request = <<<TANIA
//select * from lesson
//where (`$fieldTime` BETWEEN '$start' AND '$end') AND status='1'
//TANIA;
            $request = <<<TANIA
select l.id, l.title, l.description, l.start, l.end, l.status, l.teacher,
 u.name, u.surname from lesson as l
INNER JOIN User as u ON
l.teacher = u.id
where   (`$fieldTime` BETWEEN '$start' AND '$end') AND status='1'
TANIA;
//
            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            for($i=0;$i<count($var);$i++){
                $var[$i]['group']=$this->getAllGroupsForThisLesson($var[$i]["id"]);
            }
            return $var;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    //додає ноаві події
    public function addLesson($title, $start,$end,$id_teacher) {
        try {
            $date = $this->realDate()->format($this->formatDate());
            $this->db->query("INSERT INTO lesson (title,start,end,date,update_date,status,teacher) VALUES ('$title','$start','$end','$date','$date',1,$id_teacher)");
            return $this->db->lastInsertId();
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function updateLesson($title, $start,$end,$id,$teacherId) {
        try {
            $date = $this->realDate()->format($this->formatDate());

//            UPDATE COMPANY SET ADDRESS = 'Texas' WHERE ID = 6;
            $this->db->query("UPDATE lesson SET title='$title', start='$start',end='$end',update_date='$date',teacher='$teacherId' WHERE id=$id");

        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

    //повертає заняття для календаря на теперішній місяць
    public function getAllEvent($start,$end){
        return $this->getEventsInterval($start,$end,'start');
    }

    //Реал Тайм Апдейт
    public function getRealTimeUpdate($iteration,$userinfo){
        $end =$this->realDate();
        $start =$this->realDate();
        $myIteration = $iteration+10;
        $start=$start->modify("-$myIteration second");
        $start=$start->format($this->formatDate());
        $end=$end->format($this->formatDate());


        try {

//            print_r($userinfo);
            $id = $userinfo['id'];
            if($userinfo['title']==='teacher') {
                $res = "select l.id,
            l.title, l.date,l.description, l.start, l.end,l.status,l.teacher,u.name,u.surname
              from lesson as l
              INNER JOIN  user as u ON
              u.id = l.teacher
            WHERE  (l.start BETWEEN '$start' AND '$end') AND l.status='1'";
                $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $res = "select l.id,
            l.title, l.date,l.description, l.start, l.end,l.status,l.teacher,u.name,u.surname
            from 'student_group'as st_g
            INNER JOIN  'group_lesson' as  gr ON
            st_g.group_id=gr.group_id
            INNER JOIN 'lesson' as l ON
            l.id=gr.lesson_id
            INNER JOIN 'user' as u ON
            u.id=l.teacher
            WHERE (st_g.student_id='$id')
            AND (l.start BETWEEN '$start' AND '$end') AND l.status='1'";
                $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
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

    public function delEvent($id){
        try {
            $date = $this->realDate()->format($this->formatDate());
            $this->db->query("UPDATE lesson SET status='2',update_date='$date' WHERE id=$id");


        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function restore($id){
        try {
            $date = $this->realDate()->format($this->formatDate());
            $this->db->query("UPDATE lesson SET status='1',update_date='$date' WHERE id=$id");

            $request = <<<TANIA
select l.id, l.title, l.description, l.start, l.end, l.status, l.teacher,
 u.name, u.surname from lesson as l
INNER JOIN User as u ON
l.teacher = u.id
where l.id=$id
TANIA;

            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
//            echo $var;
            return $var;

        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function getAllGroupsForThisLesson($id)
    {

        try {
            $request = <<<BORIA
  SELECT
b.id,
b.name,
b.description,
b.teacher_id
FROM groups AS b
JOIN group_lesson AS ba ON b.id = ba.group_id
JOIN lesson AS a ON a.id = ba.lesson_id
WHERE ba.lesson_id = $id
BORIA;

            $var = $this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function addGroupToLesson($lessonId,$groupId){
        try {
            $request = <<<BORIA
            insert into group_lesson(group_id,lesson_id)values('$groupId','$lessonId')
BORIA;

            $this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
//            echo $var;
            return "ok";

        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function deleteGroupFromLesson($lessonId,$groupId){
        try {
            $request = <<<BORIA
            delete from group_lesson where group_id='$groupId' AND lesson_id='$lessonId'
BORIA;
            $this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
//            echo $var;
            return "ok";

        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }


    public function  getOurLessonForThisId($userinfo,$start,$end){
        try {

//            print_r($userinfo);
            $id = $userinfo['id'];
            if($userinfo['title']==='teacher') {
                $res = "select l.id,
            l.title, l.date,l.description, l.start, l.end,l.status,l.teacher,u.name,u.surname
              from lesson as l
              INNER JOIN  user as u ON
              u.id = l.teacher
            WHERE  (l.start BETWEEN '$start' AND '$end') AND l.status='1'";
                $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $res = "select l.id,
            l.title, l.date,l.description, l.start, l.end,l.status,l.teacher,u.name,u.surname
            from 'student_group'as st_g
            INNER JOIN  'group_lesson' as  gr ON
            st_g.group_id=gr.group_id
            INNER JOIN 'lesson' as l ON
            l.id=gr.lesson_id
            INNER JOIN 'user' as u ON
            u.id=l.teacher
            WHERE (st_g.student_id='$id')
            AND (l.start BETWEEN '$start' AND '$end') AND l.status='1'";
                $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
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

}


?>