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
where `$fieldTime` BETWEEN '$start' AND '$end'
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

            $this->db->query("INSERT INTO lesson (title,start,end,date) VALUES ('$title','$start','$end','$date')");
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
            $this->db->query("UPDATE lesson SET title='$title', start='$start',end='$end' WHERE id=$id");

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
        $start=$start->modify("-$iteration second");
//        echo $start->format($this->formatDate());
//        echo $end->format($this->formatDate());

//        $start=$start->modify("-$iteration second");
        return $this->getEventsInterval($start->format($this->formatDate()),$end->format($this->formatDate()),'date');

    }

}


?>