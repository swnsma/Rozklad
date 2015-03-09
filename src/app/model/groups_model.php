<?php

class GroupsModel extends Model
{
    public function getList()
    {
        $r = <<<HERE
        SELECT
            `groups`.`id` as group_id,
            `groups`.`name` as name,
            `groups`.`teacher_id`,
            `groups`.`archived`,
            `user`.`name` as teacher_fn,
            `user`.`surname` as teacher_ln,
            `groups`.`img_src` as photo,
        count(`student_group`.`student_id`) as descr
        FROM `groups`LEFT JOIN `student_group` ON `groups`.`id`=`student_group`.`group_id`, `user`
        WHERE `user`.`id` = `groups`.`teacher_id`
        AND `groups`.`archived` = 0
        GROUP BY `groups`.`name`ORDER BY `groups`.`id`;
HERE;
        try {
            $request = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
            return $request;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function existsGroup($name)
    {
        $r = $this->db->prepare('SELECT * FROM `groups` WHERE `name` = :n');
        $r->bindParam(':n', $name);
        if ($r->execute() && count($r->fetchAll()) == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getArchive()
    {
        $r = <<<HERE
        SELECT
            `groups`.`id` as group_id,
            `groups`.`name` as name,
            `groups`.`teacher_id`,

            `user`.`name` as teacher_fn,
            `user`.`surname` as teacher_ln,
            `groups`.`img_src` as photo
        FROM `groups`, `user`
        WHERE `user`.`id` = `groups`.`teacher_id`
        AND `groups`.`archived` = 1
HERE;

        try {
            $request = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
            return $request;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function archive($groupId, $value)
    {
        try{
           $STH=$this->db->prepare("UPDATE groups SET archived = :value WHERE id=:groupId");
            $STH->execute(array('value'=>$value,'groupId'=>$groupId));
           }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    private function createInviteCode()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function getRandomColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    public function createGroup($teacher_id, $name, $image)
    {
        try {
            $query = <<<HERE
            INSERT INTO `groups`
                (`name`, `teacher_id`, `invite_code`, `img_src`, `color`)
            VALUES
                (:name, :id, :invite, :img, :color)
HERE;
            $invite = $this->createInviteCode();
            $request = $this->db->prepare($query);
            $color = $this->getRandomColor();
            $result = $request->execute(array(
               ':name' => $name,
               ':id' => $teacher_id,
                ':invite' => $invite,
                ':img' => $image,
                ':color' => $color
            ));
            if ($result && $request->rowCount() > 0) {
                return array(
                    'key' => $invite,
                    'id' => $this->db->lastInsertId()
                );
            }
        } catch(PDOException $e) {
            print $e->getMessage();
        }
        return null;
    }

    public function getOurGroups()
    {
        try {
            $request = <<<TANIA
            select id,name,color from groups where archived=0
TANIA;

            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            return null;
        }
    }

    public function getGroups($id)
    {
        try {
            $request = <<<TANIA
            select id,name from groups where teacher_id='$id'
TANIA;

            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            return null;
        }
    }
}