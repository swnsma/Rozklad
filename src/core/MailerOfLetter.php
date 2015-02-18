<?php

require_once DOC_ROOT . 'core/Mail.php';

class MailerOfLetter {
    private static $instance = null;
    private $db;

    private function __construct() {
        $this->db = DataBase::getInstance()->DB();
    }


    private function getUsersForInvitationToLesson() {
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

    public function sendInvitationToLesson() {
        echo '<pre>';
        print_r($this->getUsersForInvitationToLesson());
        echo '</pre>';
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