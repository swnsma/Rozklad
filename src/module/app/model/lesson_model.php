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
            $request = <<<TANIA
select * from lesson
where (`$fieldTime` BETWEEN '$start' AND '$end') AND status='1'
TANIA;

            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    //додає ноаві події
    public function addLesson($title, $start,$end) {
        try {
            $date = $this->realDate()->format($this->formatDate());
            $this->db->query("INSERT INTO lesson (title,start,end,date,update_date,status) VALUES ('$title','$start','$end','$date','$date',1)");
            return $this->db->lastInsertId();
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function updateLesson($title, $start,$end,$id) {
        try {
            $date = $this->realDate()->format($this->formatDate());

//            UPDATE COMPANY SET ADDRESS = 'Texas' WHERE ID = 6;
            $this->db->query("UPDATE lesson SET title='$title', start='$start',end='$end',update_date='$date' WHERE id=$id");

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
    public function getRealTimeUpdate($iteration){
        $end =$this->realDate();
        $start =$this->realDate();
        $myIteration = $iteration+10;
        $start=$start->modify("-$myIteration second");
        $start=$start->format($this->formatDate());
        $end=$end->format($this->formatDate());
//        echo $start->format($this->formatDate());
//        echo $end->format($this->formatDate());

//        $start=$start->modify("-$iteration second");
        try {
            $request = <<<TANIA
                select * from lesson
                    where `update_date` BETWEEN '$start' AND '$end'
TANIA;
            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
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

}


?>