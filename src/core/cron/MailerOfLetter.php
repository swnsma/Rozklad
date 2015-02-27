<?php

require_once 'core/Mail.php';

class MailerOfLetter {
    private static $instance = null;
    private $db, $mail;

    private function __construct() {
        $this->db = DataBase::getInstance();
        $this->mail = Mail::getInstance();
    }

    private function getGroupsForInvitationToLesson() {
        $request = <<<HERE
            SELECT group_id, lesson_id FROM group_lesson WHERE mail = 0
HERE;
        try {
            $result = $this->db->query($request);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {}
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
        } catch(PDOException $e) {}
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
            return false;
        }
    }

    private function getTemplateForInvitationToLesson($id) {
        $request = <<<HERE
            SELECT
                `lesson`.`id` as l_id,
                `lesson`.`title` as title,
                `user`.`name` as t_name,
                `user`.`surname` as t_surname
            FROM `lesson`, `user`
            WHERE `lesson`.`id` = :id AND `lesson`.`teacher` = `user`.`id`
            LIMIT 1
HERE;
        try {
            $request = $this->db->prepare($request);
            $request->bindParam(':id', $id, PDO::PARAM_INT);
            if ($request->execute()) {
                $data = $request->fetchAll(PDO::FETCH_ASSOC);
                if (count($data) == 0) return null;
                $data = $data[0];
                $this->mail->addFileToHtml(get_include_path() . 'public/img/mail_background.png', 'mail_background');
                $this->mail->addFileToHtml(get_include_path() . 'public/img/mail_sep.png', 'mail_sep');
                return $this->mail->getTemplate('invitationToLesson', array(
                    'lessonTitle' => $data['title'],
                    'userNameTeacher' => $data['t_name'] . ' ' . $data['t_surname'],
                    'url' => 'http://test-rozklad.z-tech.com.ua/app/lesson/id' . $data['l_id'],
                    'mail_background' => 'mail_background',
                    'mail_sep' => 'mail_sep'
                ));
            }
        } catch(PDOException $e) {}
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
            $templates[$lessonId] = $this->getTemplateForInvitationToLesson($lessonId);
        }
        foreach($groups as $group) {
            $emails = $this->getEmailUsersGroups($group['group_id']);
            $emails = $this->getUniqueValuesFromKey($emails, 'email');
            if ($this->mail->send($emails, 'Приглашение', $templates[$group['lesson_id']])) {
                $this->mailAlreadySend($group['group_id'], $group['lesson_id']);
                echo 'Письма отправленные на ', implode(', ', $emails);
            } else {
                echo 'Письма не отправленые. Ошибка: ' . $this->mail->getErrorInfo();
                break;
            }
            echo str_repeat('-', 20);
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
