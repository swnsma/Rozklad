<?php

class GroupsModel extends Model {
    public function __construct() {
        parent::__construct();

    }

    public function getList() {
        $r = <<<HERE
        SELECT
            `groups`.`id` as group_id,
            `groups`.`name` as name,
            `groups`.`description` as descr,
            `user`.`name` as teacher_fn,
            `user`.`surname` as teacher_ln,
            `groups`.`img_src` as photo
        FROM `groups`, `user`
        WHERE `user`.`id` = `groups`.`teacher_id`
HERE;

        try {
            $request = $this->db->query($r);
            return $request->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    private function createInviteCode() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function getRandomColor() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    public function createGroup($teacher_id, $name, $descr, $image) {
        try {
            $query = <<<HERE
            INSERT INTO `groups`
                (`name`, `teacher_id`, `description`, `invite_code`, `img_src`, `color`)
            VALUES
                (:name, :id, :descr, :invite, :img, :color)
HERE;
            $invite = $this->createInviteCode();
            $request = $this->db->prepare($query);
            $color = $this->getRandomColor();
            $result = $request->execute(array(
               ':name' => $name,
               ':id' => $teacher_id,
                'descr' => $descr,
                ':invite' => $invite,
                ':img' => $image,
                ':color' => $color
            ));
            if ($request && $request->rowCount() > 0) {
                return array(
                    'key' => $invite,
                    'id' => $this->db->lastInsertId()
                );
            }
        } catch(PDOException $e) {}
        return null;
    }
    public function getOurGroups(){
        try {
            $request = <<<TANIA
            select id,name from groups
TANIA;

            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public function getGroups($id){
        try {
            $request = <<<TANIA
            select id,name from groups where teacher_id='$id'
TANIA;

            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
}