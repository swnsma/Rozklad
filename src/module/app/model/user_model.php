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

    static $userInfo=null;
    public function getCurrentUserInfo(){
        //$id = $_SESSION('id');
        $id = '1';
        if (is_null(self::$userInfo)){

            $sql = <<<SQL
                    select
                        u.name,
                        u.surname,
                        u.email,
                        u.phone,
                        u.fb_id,
                        u.gm_id,
                        r.title

                    from user as u
                    inner join role as r
                    on u.role_id = r.id
                    where u.id='$id'
SQL;

            self::$userInfo = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
//            echo self::$userInfo;
        }
        return self::$userInfo;
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