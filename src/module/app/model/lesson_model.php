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
            $SHT= $this->db->prepare("INSERT INTO lesson (title,start,end,date,update_date,status,teacher) VALUES (:title, :start, :end, :date , :update_date, 1, :teacher)");
            $SHT->execute(array('title'=>$title, 'start'=>$start, 'end'=>$end, 'date'=>$date , 'update_date'=>$date, 'teacher'=>$id_teacher));
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
            $SHT=$this->db->prepare("UPDATE lesson SET title=:title, start=:start,end=:end,update_date=:update_date,teacher=:teacher WHERE id=:id");
            $SHT->execute(array('title'=>$title, 'start'=>$start, 'end'=>$end, 'update_date'=>$date, 'teacher'=>$teacherId, 'id'=>$id));

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
        $myIteration = $iteration;
        $start=$start->modify("-$myIteration second");
        $start=$start->format($this->formatDate());
        $end=$end->format($this->formatDate());

//        print $start;
//        print $end;


        try {

//            print_r($userinfo);
            $id = $userinfo['id'];
            if($userinfo['title']==='teacher') {
                $res = "select l.id,
            l.title, l.date,l.description, l.start, l.end,l.status,l.teacher,u.name,u.surname
              from lesson as l
               INNER JOIN user as u ON
               l.teacher = u.id
            WHERE  (l.update_date BETWEEN '$start' AND '$end') ";
                $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
                for($i=0;$i<count($var);++$i){
                    $var[$i]['group']=$this->getAllGroupsForThisLesson($var[$i]["id"]);
                }
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
            AND (l.update_date BETWEEN '$start' AND '$end') ";
                $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
            }
            for($i=0;$i<count($var);++$i){
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

    public function delEvent($id){
        try {
            $date = $this->realDate()->format($this->formatDate());
            $SHT=$this->db->prepare("UPDATE lesson SET status='2',update_date=:update_date WHERE id=:id");
            $SHT->execute(array('update_date'=>$date, 'id'=>$id));
//            $SHT= $this->db->prepare("INSERT INTO lesson (title,start,end,date,update_date,status,teacher) VALUES (:title, :start, :end, :date , :update_date, 1, :teacher)");
//            $SHT->execute(array('title'=>$title, 'start'=>$start, 'end'=>$end, 'date'=>$date , 'update_date'=>$date, 'teacher'=>$id_teacher));


        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function restore($id){
        try {
            $date = $this->realDate()->format($this->formatDate());
            $STH = $this->db->prepare("UPDATE lesson SET status='1',update_date=:update_date WHERE id=:id");
            $STH->execute(array('update_date'=>$date,'id'=>$id));

//  $SHT= $this->db->prepare("INSERT INTO lesson (title,start,end,date,update_date,status,teacher) VALUES (:title, :start, :end, :date , :update_date, 1, :teacher)");
//            $SHT->execute(array('title'=>$title, 'start'=>$start, 'end'=>$end, 'date'=>$date , 'update_date'=>$date, 'teacher'=>$id_teacher));

            $request = <<<TANIA
select l.id, l.title, l.description, l.start, l.end, l.status, l.teacher,
 u.name, u.surname from lesson as l
INNER JOIN User as u ON
l.teacher = u.id
where l.id=$id
TANIA;

            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            for($i=0;$i<count($var);$i++){
                $var[$i]['group']=$this->getAllGroupsForThisLesson($var[$i]["id"]);
            }

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
b.teacher_id,
b.color
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

BORIA;


            $STH = $this->db->prepare(" insert into group_lesson(group_id,lesson_id)values(:group_id , :lesson_id)");
            $STH->execute(array('lesson_id'=>$lessonId,'group_id'=>$groupId));

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

//            echo $var;
            $STH = $this->db->prepare("delete from group_lesson where group_id=:group_id AND lesson_id=:lesson_id");
            $STH->execute(array('lesson_id'=>$lessonId,'group_id'=>$groupId));
            return "ok";

        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }


    public function  getOurLessonForThisIdStudent($userinfo,$start,$end){
        try {

//            print_r($userinfo);
            $id = $userinfo['id'];
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

    public function  getOurLessonForThisIdTeacherCurrent($userinfo,$start,$end){
        try {

//            print_r($userinfo);
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
//            print_r($result);
            return $result;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }




    static public function realDeletedLesson(){
        $start = date("2014-01-01");
        $start = new DateTime($start);
        $start=$start->format('Y-m-d H:i:s');


        $var =date("Y-m-d H:i:s");
        $var1=new DateTime($var);
        $var1=$var1->modify("-1 day");
        $var1=$var1->format('Y-m-d H:i:s');
        $db = DataBase::getInstance()->DB();
        try {
            $db->query("DELETE FROM 'lesson'
 WHERE  update_date BETWEEN '$start' AND '$var1' AND status='2'");
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }

    }

}


?>