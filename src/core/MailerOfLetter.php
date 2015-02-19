<?php

require_once DOC_ROOT . 'core/Mail.php';

class MailerOfLetter {
    private static $instance = null;
    private $db, $mail;

    private function __construct() {
        $this->db = DataBase::getInstance()->DB();
        $this->mail = Mail::getInstance();
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
            if ($request->execute()) return $request->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            print $e->getMessage();
        }
        return null;
    }

    private function mailAlreadySend($group_id, $lesson_id) {
        $request = <<<HERE
        UPDATE group_lesson
        SET `mail` = 1
        WHERE group_id = :gid AND lesson_id = :lid
HERE;
        try {
            $request = $this->db->prepare($request);
            $request->bindParam(':gid', $group_id, PDO::PARAM_INT);
            $request->bindParam(':lid', $lesson_id, PDO::PARAM_INT);
            return $request->execute();
        } catch(PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function getTemplateForInvitationToLesson($id) {
        print $id;
        $request = <<<HERE
            SELECT
                `lesson`.`title` as title
            FROM `lesson`
            WHERE `lesson`.`id` = :id
            LIMIT 1
HERE;
        try {
            $request = $this->db->prepare($request);
            $request->bindParam(':id', $id, PDO::PARAM_INT);
            if ($request->execute()) {
                $data = $request->fetchAll(PDO::FETCH_ASSOC);
                $data = $data[0];
                return $this->mail->getTemplate('invitationToLesson', array(
                    'title' => $data['title']
                ));
            }
        } catch(PDOException $e) {
            print $e->getMessage();
        }
        return null;
    }

    private function getUniqueValuesFromKey($array, $key) {
        $newArray = array();
        foreach($array as $value) {
            array_push($newArray, $value[$key]);
        }
        return array_unique($newArray);
    }

    public function sendInvitationToLesson() {
        $groups = $this->getGroupsForInvitationToLesson();
        $uniqueLessonsId = $this->getUniqueValuesFromKey($groups, 'lesson_id');
        $templates = array();
        foreach($uniqueLessonsId as $lessonId) {
            print $lessonId;
            $templates[$lessonId] = $this->getTemplateForInvitationToLesson($lessonId);
        }
        foreach($groups as $group) {
            $emails = $this->getEmailUsersGroups($group['group_id']);
            $emails = $this->getUniqueValuesFromKey($emails, 'email');
            if ($this->mail->send($emails, 'Приглашение', $templates[$group['lesson_id']])) {
                if ($this->mailAlreadySend($group['group_id'], $groups['lesson_id'])) {
                    echo 'update';
                } else {
                    echo 'update_error';
                }
                echo 'Письма отправленные';
            } else {
                echo 'Письма не отправленые' . $this->mail->getErrorInfo();
            }
            print_r($emails);
            echo '<hr/>';
            $this->mail->clear();
        }
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