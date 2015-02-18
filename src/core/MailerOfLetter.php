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
            SELECT * FROM group_lesson
HERE;

        $result = $this->db->query($request);
        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
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