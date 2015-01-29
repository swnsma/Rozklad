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

}