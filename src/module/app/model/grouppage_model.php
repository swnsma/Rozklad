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
        $this->setGroupId($idGroup);
        $var=$this->db->query("SELECT user.name, surname FROM user INNER JOIN groups ON user.id=groups.teacher_id WHERE groups.id=$idGroup;")->fetchAll(PDO::FETCH_ASSOC);
        $this->setTeacherName($var[0]['name'].' '.$var[0]['surname']);
        $var=$this->db->query("SELECT id, user.name, surname FROM user INNER JOIN student_group ON user.id=student_group.student_id WHERE student_group.group_id=$idGroup;")->fetchAll(PDO::FETCH_ASSOC);
        $this->setUsers($var);
        $var = $this->db->query("SELECT name FROM groups WHERE id=$idGroup")->fetchAll(PDO::FETCH_ASSOC);
        $this->setName($var[0]);
        $var = $this->db->query("SELECT description FROM groups WHERE id=$idGroup;")->fetchAll(PDO::FETCH_ASSOC);
        $this->setDescription($var[0]);
        $var = $this->db->query("SELECT invite_code FROM groups WHERE id=$idGroup;")->fetchAll(PDO::FETCH_ASSOC);
        $this->setInviteCode($var[0]);
       }
       catch(PDOException $e){
           echo $e->getMessage();
       }
    }
   public function delUser(){
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
   public function renameGroup($newName){
       $id=$this->getGroupId();
       try{
       $STH=$this->db->prepare("UPDATE groups SET name = :name WHERE id=:id;");
       $STH->execute(array('name'=>$newName, 'id'=>$id));
       }
       catch(PDOException $e){
           echo $e->getMessage();
       }
       $this->setName($newName);
   }
    public function createInviteCode(){
        $id=$this->getGroupId();
        try{
            $code=md5($id.time());
            $this->db->query("UPDATE groups SET invite_code = '$code' WHERE id=$id;");
            $this->setInviteCode($code);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function editDescription($newDescription){
        $id=$this->getGroupId();
        try{
            $STH=$this->db->prepare("UPDATE groups SET description = :description WHERE id=:id;");
            $STH->execute(array('description'=>$newDescription, 'id'=>$id));
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
        $this->setDescription($newDescription);
    }
}