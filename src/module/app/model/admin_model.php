<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 27.01.2015
 * Time: 17:00
 */
class AdminModel extends Model {
    public function __construct() {
        parent::__construct();

    }

    public function confirmUser($id) {
        try {
            $this->db->query("DELETE FROM unconfirmed_user WHERE id=$id;");
            $d=$this->db->query("SELECT `name`, surname, `key`, email FROM `user` WHERE id=$id;");
            $this->db->query("UPDATE user SET key='1' WHERE id=$id;");
            $d->fetchAll(PDO::FETCH_ASSOC);
            return $d;
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function unConfirmUser($id) {
        try {
            $this->db->query("INSERT INTO unconfirmed_user (id) VALUES ($id)");
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function getUnconfirmedUsers(){
        try {
            $sql = <<<SQL
                select
                    user.name,
                    user.surname,
                    user.email,
                    user.phone,
                    user.fb_id,
                    user.gm_id,
                    user.id,
                    role.title
                from user
                inner join role
                on user.role_id = role.id
                where user.role_id='1'
--                 inner join unconfirmed_user
--                 on user.id = unconfirmed_user.id
SQL;

            $var = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }
    public function getTeachers(){
        try {
            $sql = <<<SQL
                select
                    user.name,
                    user.surname,
                    user.email,
                    user.phone,
                    user.fb_id,
                    user.gm_id,
                    user.id,
                    role.title,
                    unc.id as unc_id
                from user
                inner join role
                on user.role_id = role.id
                left outer join unconfirmed_user as unc
                on user.id = unc.id
                where user.role_id='1'
SQL;

            $var = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return $var;
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

}