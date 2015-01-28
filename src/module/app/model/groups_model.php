<?php

class GroupsModel extends Model {
    public function __construct() {
        parent::__construct();

    }

    public function getList() {
        $r = <<<HERE
        SELECT
            `group`.`id` as group_id,
            `group`.`name` as name,
            `user`.`name` as teacher_name
        FROM `group`, `user`
        WHERE `user`.`id` = `group`.`teacher_id`
HERE;
        try {
            $request = $this->db->query($r);
            return $request->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

}