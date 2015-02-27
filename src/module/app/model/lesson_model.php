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
                    $var[$i]['newdz']=$this->getNewDZ($var[$i]["id"]);
                }
            }else{
                $res = "select l.id,
            l.title, l.date,l.description, l.lesson_info, l.start, l.end,l.status,l.teacher,u.name,u.surname
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

    public function addGroupToLesson($lessonId,$groupId){
        try {
            $request = <<<BORIA

BORIA;


            $STH = $this->db->prepare(" insert into group_lesson(group_id,lesson_id, mail)values(:group_id , :lesson_id, 0)");
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
            l.title,l.deadline,l.lesson_info, l.date,l.description, l.start, l.end,l.status,l.teacher,u.name,u.surname
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

            AND (l.start BETWEEN '$start' AND '$end') AND l.status='1' AND g.archived=0
              ORDER BY l.deadline ASC ";
            $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
            for($i=0;$i<count($var);$i++){
                $var[$i]['group']=$this->getAllGroupsForThisLesson($var[$i]["id"]);
                $lesson_id=$var[$i]['id'];
                $res = "select * from 'result' as r where r.owner='$id' AND r.lesson_id='$lesson_id'";
                $var[$i]['estimate'] = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);

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

    private function getNewDZ($idLesson){
        try {


            $res = "select COUNT (*) as len FROM result
WHERE result.lesson_id=$idLesson AND  result.grade=''";
            $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);

//            print_r($result);
            return $var;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public function  getOurLessonForThisIdTeacherCurrent($userinfo,$start,$end){
        try {
            $id = $userinfo['id'];

            $res = "select l.id,
            l.title, l.date,l.description, l.start, l.end,l.status,l.teacher,u.name,u.surname
              from lesson as l
              INNER JOIN  user as u ON
              (u.id = l.teacher) AND u.id='$id'
            WHERE  (l.start BETWEEN '$start' AND '$end') AND l.status='1'" ;
            $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
            for($i=0;$i<count($var);$i++){
                $var[$i]['group']=$this->getAllGroupsForThisLesson($var[$i]["id"]);
                $var[$i]['newdz']=$this->getNewDZ($var[$i]["id"]);
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

    public function existLesson($id,$userInfo){
        try{
            if($userInfo['title']==='teacher') {
                $var = $this->db->prepare("SELECT * FROM lesson where (id=:id and status=1)");
                $var->execute(array(':id' => $id));
                $var1 = $var->fetchAll();
                if (isset($var1[0])) {
                    return true;
                } else {
                    return false;
                }
            }
            else{
                $var = $this->db->prepare("SELECT * FROM user as u
                                               INNER JOIN student_group as sg ON
                                               u.id=sg.student_id
                                                INNER JOIN group_lesson as gl ON
                                                gl.group_id=sg.group_id
                                                INNER JOIN lesson as l ON
                                                l.id=gl.lesson_id
                                                where (l.id=:id AND l.status=1 and u.id = :UID)");
                $var->execute(array('id' => $id, 'UID'=>$userInfo['id']));
                $var1 = $var->fetchAll();
                if (isset($var1[0])) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        catch(PDOException $e){
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

    public function exportEvent($lessonId, $userId, $calendarId, $eventId=null){
        $client = new Google_Client();
        $client->setApplicationName("Rozklad");
        $client->setClientId(CLIENT_ID_GM);
        $client->setClientSecret(CLIENT_SECRET_GM);
        $client->setRedirectUri(URL . "app/loging/login");
        $client->setApprovalPrompt(APPROVAL_PROMPT);
        $client->setAccessType(ACCESS_TYPE);
        $client->setAccessToken(Session::get('token'));
        $service = new Google_Service_Calendar($client);

        function wasExported($lessonId, $userId, $calendarId, $db)
        {
            $request = <<<SQL
select * from exported_events
where lesson_id = "$lessonId"
and user_id = "$userId"
and calendar_id = "$calendarId";
SQL;
            echo $request;
            $exp = $db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $exp;
        }

        $exported = wasExported($lessonId, $userId, $calendarId, $this->db);

        function getLesson($lessonId, $db){
            $request = <<<SQL
select * from lesson
where lesson.id = $lessonId;
SQL;
            $lesson = $db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            $lesson = $lesson[0];
            return $lesson;
        }

        $lesson = getLesson($lessonId, $this->db);

        $event = new Google_Service_Calendar_Event();
        $event->setSummary($lesson['title']);

        $start = new Google_Service_Calendar_EventDateTime();
        $date =  $lesson['start'];
        $date = str_replace(' ','T',$date);
        $start->setDateTime($date);
        $start->setTimeZone(TIME_ZONE);
        $event->setStart($start);

        $end = new Google_Service_Calendar_EventDateTime();
        $date =  $lesson['end'];
        $date = str_replace(' ','T',$date);
        $end->setDateTime($date);
        $end->setTimeZone(TIME_ZONE);
        $event->setEnd($end);

        $new_event = null;
        echo json_encode($exported);
        if (!empty($exported)){
            try {
                $new_event = $service->events->update($calendarId, $exported[0]['event_id'], $event);
            } catch (Google_ServiceException $e) {
                echo json_encode( ($e->getMessage()) );
            }
        } else {
            try {
                $new_event = $service->events->insert($calendarId, $event);
            } catch (Google_ServiceException $e) {
                echo json_encode( ($e->getMessage()) );
            }
            $e_id = $new_event->getId();
            $request = <<<SQL
INSERT INTO exported_events ("user_id", "lesson_id", "calendar_id", "event_id")
VALUES ($userId, $lessonId, "$calendarId", "$e_id");
SQL;
            echo $request;
            $this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            //echo 'made insertion';
        }
    }

    public function getGoogleCalendarList(){
        $client = new Google_Client();
        $client->setApplicationName("Rozklad");
        $client->setClientId(CLIENT_ID_GM);
        $client->setClientSecret(CLIENT_SECRET_GM);
        $client->setRedirectUri(URL . "app/loging/login");
        $client->setApprovalPrompt(APPROVAL_PROMPT);
        $client->setAccessType(ACCESS_TYPE);
        $client->setAccessToken(Session::get('token'));
        $service = new Google_Service_Calendar($client);

        $calendarList = $service->calendarList->listCalendarList();

        $list = [];
        $i = 0;
        while(true) {
            foreach ($calendarList->getItems() as $calendarListEntry) {
                $list[$i]['name'] = $calendarListEntry->getSummary();
                $list[$i]['id'] = $calendarListEntry->getId();
                $i++;
            }
            $pageToken = $calendarList->getNextPageToken();
            if ($pageToken) {
                $optParams = array('pageToken' => $pageToken);
                $calendarList = $service->calendarList->listCalendarList($optParams);
            } else {
                break;
            }
        }
        return $list;
    }

    function setLastVisit($user_id,$lesson_id,$date){
        try {
            $db=$this->db->prepare("Select * from last_time_visit where user_id=:user_id and lesson_id=:lesson_id");
            $db->execute(array('user_id'=>$user_id, 'lesson_id'=>$lesson_id));
            $res=$db->fetchAll();
            if($res){
                $db1=$this->db->prepare("UPDATE last_time_visit SET last_visit=:date WHERE user_id=:user_id AND lesson_id=:lesson_id");
                $db1->execute(array( 'date'=>$date, 'user_id'=>$user_id, 'lesson_id'=>$lesson_id));
            }
            else{
                $db2=$this->db->prepare("INSERT INTO last_time_visit (user_id,lesson_id,last_visit) VALUES (:user_id,:lesson_id,:date)");
                $db2->execute(array( 'date'=>$date, 'user_id'=>$user_id, 'lesson_id'=>$lesson_id));
            }
            return "success";
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function allUnreaded($userinfo,$since=NULL){
        $allLessons = $this->allLessons($userinfo);
        for($i=0;$i<count($allLessons);$i++){
            $lastTime = $lastTime = $allLessons[$i]['last_visit'];
            if(isset($since)&&!empty($since)){
                $lastTime=$since;
            }
            $allLessons[$i]['mess'] =$this->getAllCommentsForLesson($allLessons[$i]['id'],$lastTime);
        }
        return $allLessons;
    }

    public function  allLessons($userinfo){
        try {
            $id = $userinfo['id'];
            if($userinfo['title']==='teacher') {
                $res = "select l.id, l.title, l.start,
              lvl.last_visit
              from lesson as l
               INNER JOIN user as u ON
               l.teacher = u.id
               LEFT JOIN last_time_visit as lvl ON
              ((l.id = lvl.lesson_id)AND(u.id = lvl.user_id))
            WHERE  u.id=$id";
                $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $res = "select l.id, l.title,l.start,
            lvl.last_visit
            from 'student_group'as st_g
            INNER JOIN  'group_lesson' as  gr ON
            st_g.group_id=gr.group_id
            INNER JOIN 'lesson' as l ON
            l.id=gr.lesson_id
            INNER JOIN 'user' as u ON
            u.id=l.teacher
            LEFT JOIN last_time_visit as lvl ON
              ((l.id = lvl.lesson_id)AND(u.id = lvl.user_id))
            WHERE (st_g.student_id='$id')";
                $var = $this->db->query($res)->fetchAll(PDO::FETCH_ASSOC);
            }
            $result = array_unique($var,SORT_REGULAR);
            sort($result);
            return $result;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function saveMess($lesson_id,$user_id,$date,$text){
        try{
            $date = strtotime($date);
            $db=$this->db->prepare("INSERT INTO comment (lesson_id,user_id,date,text) VALUES (:lesson_id,:user_id,:date,:text)");
            $db->execute(array('date'=>$date,'user_id'=>$user_id,'lesson_id'=>$lesson_id,'text'=>$text));
            return "success";
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function getAllCommentsForLesson($lesson_id,$since){
        try{
            $id = Session::get("id");
            $db=$this->db->prepare("SELECT date,status FROM comment WHERE  date >= :since AND lesson_id = :lesson_id AND status = 1 AND user_id != :id ");
            $db->execute(array('since'=>intval($since), 'lesson_id'=>$lesson_id, 'id'=>$id));
            return $db->fetchAll();
        }catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
}
