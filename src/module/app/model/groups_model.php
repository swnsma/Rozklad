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
            `user`.`name` as teacher_name
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

    public function createGroup($teacher_id, $name, $descr) {
        try {
            $query = <<<HERE
            INSERT INTO `groups`
                (`name`, `teacher_id`, `description`, `invite_code`)
            VALUES
                (:name, :id, :descr, :invite)
HERE;
            $invite = $this->createInviteCode();
            $request = $this->db->prepare($query);
            $result = $request->execute(array(
               ':name' => $name,
               ':id' => $teacher_id,
                'descr' => $descr,
                ':invite' => $invite
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
}