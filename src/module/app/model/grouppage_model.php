<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 1/28/2015
 * Time: 3:54 PM
 */

class GroupPageModel extends Model {
   public function __construct() {
        parent::__construct();
    }
    public function getGroupByCode($code){
        $r=<<<REQUEST
        SELECT *
        FROM `groups`
        WHERE `groups`.`invite_code`='$code';
REQUEST;
        $var = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
        if(isset($var[0]))
            return $var[0];
        else return null;
    }
    public function getRole ($groupId, $userId){
        $r=<<<QUERY
            SELECT `role`.`title`
FROM `role`, `user`, `groups`, `student_group`
WHERE `user`.`role_id` = `role`.`id` AND `user`.`id`=$userId AND (( `groups`.`id`=$groupId AND `groups`.`teacher_id`=$userId)
OR(`user`.`id`=`student_group`.`student_id` AND `student_group`.`group_id`=$groupId));
QUERY;
        $var = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
        if(isset($var[0]))
        return $var[0]['title'];
        else return null;

    }
   public function loadData($groupId){
       try{
        $r=<<<HERE
        SELECT
            `groups`.`description`,
            `groups`.`name`
        FROM `groups`, `user`
        WHERE `groups`.`id`=$groupId ;
HERE;
           $var=$this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
           $var[0]['teacher']=$var[0]['teacher'].' '.$var[0]['surname'];
           unset($var[0]['surname']);
           $this->setGroupInfo($var[0]);
           return $var[0];
       }
       catch(PDOException $e){
           echo $e->getMessage();
           return null;
       }
    }
   public function loadUsers($groupId){
        try{
        $r = <<<HERE
        SELECT
            `user`.`id` as id,
            `user`.`fb_id` as fb_id,
            `user`.`name` as name,
            `user`.`surname` as surname,
            `user`.`gm_id` as gm_id
        FROM `student_group`, `user`
        WHERE `user`.`id` = `student_group`.`student_id`AND `student_group`.`group_id`=$groupId;
HERE;
        $var = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
        for($i=0; $i<count($var); $i++)
        {
            $var[$i]['name']=$var[$i]['name'].' '.$var[$i]['surname'];
            unset($var[$i]['surname']);
        }
        $this->setUsers($var);
            return $var;
        }catch(PDOException $e){
            echo $e->getMessage();
            return null;
        }
    }
   public function loadSchedule($groupId){
        try{
        $r = <<<HERE
        SELECT
         `lesson`.`title` as title,
         `user`.`name` as teacher_name,
         `user`.`surname` as surname,
         `lesson`.`start` as date,
         `lesson`.`end`
         FROM `user`, `lesson`, `groups`, `group_lesson`
         WHERE `user`.`id`=`groups`.`teacher_id` AND `group_lesson`.`group_id`= $groupId AND `lesson`.`id`=`group_lesson`.`lesson_id`;
HERE;
        $var = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
        for($i=0; $i<count($var); $i++)
        {
            $var[$i]['teacher_name']=$var[$i]['teacher_name'].' '.$var[$i]['surname'];
            unset($var[$i]['surname']);
            $var[$i]['date']=substr($var[$i]['date'], 0, -3).'-'.substr(preg_replace("/[0-9]*-[0-9]*-[0-9]* /","", $var[$i]['end']), 0, -3);
            unset($var[$i]['end']);
        }
        $this->setSchedule($var);
            return $var;
        }catch(PDOException $e){
            echo $e->getMessage();
            return null;
        }
    }
   public function delUser($id, $groupId){
       if(isset($id));

       try{
       $this->db->query("DELETE FROM student_group WHERE student_id='$id' AND group_id=$groupId");
       }
       catch(PDOException $e){
           echo $e->getMessage();
       }
       /*$var = $this->getUsers();
       for ($i=0; $i<count($var); $i++){
           if($var[$i]['id']==$id){
              array_splice($var, $i, 1);
               break;
           }
       }
       $this->setUsers($var);*/
   }
   public function renameGroup($id, $newName){
       try{
       $STH=$this->db->prepare("UPDATE groups SET name = :name WHERE id=:id;");
       $STH->execute(array('name'=>$newName, 'id'=>$id));
       }
       catch(PDOException $e){
           echo $e->getMessage();
       }
       $this->setName($newName);
   }
   public function createInviteCode($id){
        try{
            $code=md5($id.time());
            $this->db->query("UPDATE groups SET invite_code = '$code' WHERE id=$id;");
            $this->setInviteCode($code);
            return $code;
        }
        catch(PDOException $e){
            echo $e->getMessage();
            return null;
        }
    }
   public function loadCode($id){
        try{
        $r=<<<HERE
        SELECT invite_code
        FROM groups
        WHERE id=$id;
HERE;
            $var=$this->db->query($r)->fetchAll(PDO::FETCH_ASSOC)[0]['invite_code'];
            $this->setInviteCode($var);

            return $var;
        }catch(PDOException $e){
            echo $e->getMessage();
            return null;
        }


    }
   public function editDescription($id,$newDescription){
        try{
            $STH=$this->db->prepare("UPDATE groups SET description = :description WHERE id=:id;");
            $STH->execute(array('description'=>$newDescription, 'id'=>$id));
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
        $var=$this->getGroupInfo($newDescription);
        $var['description']=$newDescription;
        $this->setGroupInfo($var);
    }
   public function addUserToGroup($id, $code){
       $r=<<<CHECKCODE
       SELECT `id`
       FROM `groups`
       WHERE `invite_code`='$code';
CHECKCODE;
       $groupId= $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
       if(isset($groupId[0]['id'])){
           $groupId=$groupId[0]['id'];
       $r=<<<CHECKUSER
        SELECT `student_group`.`student_id`
        FROM `student_group`, `user`
        WHERE `student_id`=$id AND `group_id`=$groupId;
CHECKUSER;
           $var=$this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
           if(!isset($var[0])){
               $r=<<<CHECKTEACHER
                    SELECT `user`.`role_id`
                    FROM `user`
                    WHERE `user`.`id`=$id;
CHECKTEACHER;
               $var=$this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
               if(isset($var[0])&&$var[0]['role_id']!=1){
               $r=<<<ADDUSER
           INSERT INTO `student_group` (student_id, group_id) VALUES ($id, $groupId);
ADDUSER;
               $this->db->query($r);
              return 0;
               }
               else{
                   return 4;
               }
           }else{
              return 1;
           }
       }else{
           return 2;
       }

   }
}
