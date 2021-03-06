<?php

class GroupPageModel extends Model {
   public function existGroup($id)
   {
       $r= <<<CHECKING
        SELECT *
        FROM `groups`
        WHERE `groups`.`id`='$id';
CHECKING;
       try {
       $var= $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
           if(isset($var[0])) {
               return true;
           } else {
               return false;
           }
       } catch(PDOException $e){
           echo $e->getMessage();
           return null;
       }
   }

   public function getGroupByCode($code)
   {
        $r=<<<REQUEST
        SELECT *
        FROM `groups`
        WHERE `groups`.`invite_code`='$code';
REQUEST;
       try {
            $var = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
            if(isset($var[0])) {
                return $var[0];
            } else {
                return null;
            }
       } catch(PDOException $e) {
           echo $e->getMessage();
           return null;
       }
   }

   public function getRole ($groupId, $userId)
   {
       $r=<<<CHECKTEACH
       SELECT `role`.`title`
       FROM `role`, `user`, `groups`
       WHERE `user`.`id`=$userId AND `user`.`role_id`=1 AND `groups`.`id`=$groupId AND `groups`.`teacher_id`=$userId AND `role`.`id`=`user`.`role_id`
CHECKTEACH;
       $r2=<<<CHECKSTUD
       SELECT `role`.`title`
       FROM `role`, `user`, `student_group`
       WHERE `student_group`.`student_id`=$userId AND `user`.`role_id`=0 AND `role`.`id`=0 AND `student_group`.`group_id`=$groupId
CHECKSTUD;
       try{
            $var = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
            if(isset($var[0])) {
                return $var[0]['title'];
            } else {
                $var=$this->db->query($r2)->fetchAll(PDO::FETCH_ASSOC);
                if(isset($var[0]))
                    return $var[0]['title'];
            }
       } catch(PDOException $e) {
           echo $e->getMessage();
           return null;
       }
   }

   public function loadData($groupId)
   {
       try {
        $r=<<<HERE
        SELECT
            `groups`.`name`,
            `groups`.`img_src`
        FROM `groups`, `user`
        WHERE `groups`.`id`=$groupId ;
HERE;
           $var=$this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
           return $var[0];
       } catch(PDOException $e) {
           echo $e->getMessage();
           return null;
       }
   }

   public function loadUsers($groupId)
   {
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
            for($i=0; $i<count($var); $i++) {
                $var[$i]['name']=$var[$i]['name'].' '.$var[$i]['surname'];
                unset($var[$i]['surname']);
            }
            $this->setUsers($var);
                return $var;
        } catch(PDOException $e){
            echo $e->getMessage();
            return null;
        }
    }

    public function delUser($id, $groupId)
    {
       $r=<<<CHECK
        SELECT *
        FROM `student_group`
        WHERE `student_id`=$id AND `group_id`=$groupId;
CHECK;
       try {
           $var=$this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
           if(isset($var[0]));
           $this->db->query("DELETE FROM student_group WHERE student_id='$id' AND group_id=$groupId");
       } catch(PDOException $e) {
           echo $e->getMessage();
       }
    }

    public function checkName($name)
    {
        try {
            $r=<<<NAMECHECK
            SELECT *
            FROM `groups`
            WHERE `groups`.`name`='$name';
NAMECHECK;
            $var = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
            if(isset($var[0])) {
                return true;
            }else{
                return false;
            }
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function renameGroup($id, $newName)
    {
        try {
            $STH=$this->db->prepare("UPDATE groups SET name = :name WHERE id=:id;");
            $STH->execute(array('name'=>$newName, 'id'=>$id));
        } catch(PDOException $e) {
           echo $e->getMessage();
        }
    }

   public function createInviteCode($id)
   {
        try {
            $code=md5($id.time());
            $this->db->query("UPDATE groups SET invite_code = '$code' WHERE id=$id;");
            $this->setInviteCode($code);
            return $code;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
   }

   public function loadCode($id) {
        try {
            $r=<<<HERE
                SELECT invite_code
                FROM groups
                WHERE id=$id;
HERE;
            $var=$this->db->query($r)->fetchAll(PDO::FETCH_ASSOC)[0]['invite_code'];
            $this->setInviteCode($var);

            return $var;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function deletePhoto($id)
    {
        $r=<<<CHECKING
           SELECT `img_src`
           FROM `groups`
           WHERE id=$id;
CHECKING;
        $var = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
        if(isset($var[0])&&!empty($var[0]['img_src'])) {
            unlink(DOC_ROOT.'public/users_files/images/groups_photo/'.$var[0]['img_src']);
            unlink(DOC_ROOT.'public/users_files/images/groups_photo/small_'.$var[0]['img_src']);
            return true;
        } else {
            return false;
        }
    }

    public function changeImage($id, $img)
    {
        $r=<<<QUER
            UPDATE `groups`
            SET `img_src` = :img
            WHERE id=:id
QUER;
        $STH = $this->db->prepare($r);
        $STH->execute(array('id'=>$id, 'img'=>$img));

    }

    public function addUserToGroup($id, $code)
    {
       $r=<<<CHECKCODE
       SELECT `id`
       FROM `groups`
       WHERE `invite_code`='$code';
CHECKCODE;
       try {
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
                   if(isset($var[0])&&$var[0]['role_id']!=1) {
                       $r=<<<ADDUSER
                            INSERT INTO `student_group` (student_id, group_id) VALUES ($id, $groupId);
ADDUSER;
                        $this->db->query($r);
                        return 0;
                    } else {
                       return 4;
                    }
               } else {
                   return 1;
               }
           } else {
            return 2;
           }
       } catch(PDOException $e) {
           echo $e->getMessage();
           return null;
       }
   }

   public function addUser($id, $userId)
   {
       try {
           $r=<<<CHECK
            SELECT * FROM `student_group`
            WHERE `student_id`=$id AND `group_id`= $userId;
CHECK;
           $var= $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
           if(!isset($var[0])) {
               $r=<<<ADD
                 INSERT INTO `student_group` (`student_id`, `group_id`) VALUES ($userId,$id);
ADD;

                $this->db->query($r);
           }
       } catch(PDOException $e) {
           echo $e->getMessage();
       }
   }
}
