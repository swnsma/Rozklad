<?php

require_once DOC_ROOT . 'core/Mail.php';

class MailerOfLetter {
    private static $instance = null;
    private $db;

    private function __construct() {
        $this->db = DataBase::getInstance()->DB();
    }

    private function getGroupsForInvitationToLesson() {
        $request = <<<HERE
            SELECT group_id, lesson_id FROM group_lesson WHERE mail = 0
HERE;
        try {
            $result = $this->db->query($request);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            print $e->getMessage();
        }
        return null;
    }

    private function getEmailUsersGroups($id) {
        $request = <<<HERE
            SELECT
                `user`.`email` as email
            FROM `user`
            WHERE `user`.`id` IN (
                SELECT
                    `student_group`.`student_id` as id
                FROM `student_group`
                WHERE `student_group`.`group_id` = :id
                )
HERE;
        try {
            $request = $this->db->prepare($request);
            $request->bindParam(':id', $id, PDO::PARAM_INT);
            if ($request->execute()) return $request->fetchColumn(0);
        } catch(PDOException $e) {
            print $e->getMessage();
        }
        return null;
    }

    public function sendInvitationToLesson() {
        $groups = $this->getGroupsForInvitationToLesson();
        foreach($groups as $group) {
            $emails = $this->getEmailUsersGroups($group['group_id']);

            echo '<pre>';
            print_r($emails);
            echo '</pre>';
        }

        /*

        echo '<pre>';
        print_r($emails);
        echo '</pre>';*/
    }

    static public function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone() {}
}


?>