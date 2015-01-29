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
   public function loadData($idGroup){
       try{


        /*$this->setGroupId($idGroup);
        $var=$this->db->query("SELECT user.name, surname FROM user INNER JOIN groups ON user.id=groups.teacher_id WHERE groups.id=$idGroup;")->fetchAll(PDO::FETCH_ASSOC);
        $this->setTeacherName($var[0]['name'].' '.$var[0]['surname']);
        $var=$this->db->query("SELECT id, user.name, surname FROM user INNER JOIN student_group ON user.id=student_group.student_id WHERE student_group.group_id=$idGroup;")->fetchAll(PDO::FETCH_ASSOC);
        $this->setUsers($var);
        $var = $this->db->query("SELECT name FROM groups WHERE id=$idGroup")->fetchAll(PDO::FETCH_ASSOC);
        $this->setName($var[0]);
        $var = $this->db->query("SELECT description FROM groups WHERE id=$idGroup;")->fetchAll(PDO::FETCH_ASSOC);
        $this->setDescription($var[0]);
        $var = $this->db->query("SELECT invite_code FROM groups WHERE id=$idGroup;")->fetchAll(PDO::FETCH_ASSOC);
        $this->setInviteCode($var[0]);*/


        $r=<<<HERE
        SELECT
            `groups`.`description`,
            `groups`.`name`,
            `user`.`name` as teacher,
            `user`.`surname`
        FROM `groups`, `user`
        WHERE `groups`.`id`=$idGroup AND `groups`.`teacher_id`=`user`.`id`;
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
            `user`.`id` as user_id,
            `user`.`name` as name,
            `user`.`surname` as surname
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
   public function delUser($id){
       if(isset($id));
       $id=$this->getGroupId();
       try{
       $this->db->query("DELETE FROM student_group WHERE student_id=$id;");
       }
       catch(PDOException $e){
           echo $e->getMessage();
       }
       $var = $this->getUsers();
       for ($i=0; $i<count($var); $i++){
           if($var[$i]['id']==$id){
              array_splice($var, $i, 1);
               break;
           }
       }
       $this->setUsers($var);
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
            $var=$this->db->query($r);
            $this->setInviteCode($var[0]);
            return $var[0];
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
}
//$var = new GroupPageModel();