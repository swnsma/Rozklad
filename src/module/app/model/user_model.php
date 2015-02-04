<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 25.01.2015
 * Time: 20:38
 */
class UserModel extends Model {
    public function __construct() {
        parent::__construct();

    }

    public function getCurrentUserInfo(){
        static $userInfo;

        $id = $_SESSION['id'];
//        $id = '4';
        if (is_null($userInfo)){
            $userInfo = array();
            $sql = <<<SQL
                    select
                        user.name,
                        user.surname,
                        user.email,
                        user.phone,
                        user.fb_id,
                        user.gm_id,
                        role.title
                    from user
                    inner join role
                    on user.role_id = role.id
                    where user.id='$id'
SQL;
            $userInfo = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC)[0];
        }

        return $userInfo;
    }

    public function getInfoFB($fb_id){
        try {
            $request = <<<TANIA
select * from user
where fb_id='$fb_id'
TANIA;
            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            if(isset($var[0]))
            return $var[0];
            else return null;
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
    public function getInfoGM($gm_id){
        try {
            $request = <<<TANIA
            select * from user
            where gm_id='$gm_id'
TANIA;

            $var =$this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var[0];
        } catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
}
?>