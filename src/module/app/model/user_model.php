<?php


class UserModel extends Model
{
    public function getUserInfo($id)
    {
        try {
            $d = $this->db->query("SELECT* FROM `user` WHERE id=$id;")->fetchAll(PDO::FETCH_ASSOC);
            if (isset($d[0])) {
                return $d[0];
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getUserInformation($id)
    {
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
                where user.id='$id'
SQL;
        $info = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $info = $info[0];

        if ($info['fb_id']) {
            $info['photoFB'] = "http://graph.facebook.com/".$info['fb_id']."/picture?width=150&height=150";
            $info['photo'] = $info['photoFB'];
        }

        if ($info['gm_id']) {
            $info['photoGM'] = $this->getGooglePhotoByGId($info['gm_id']);
            $info['photo'] = $info['photoGM'];
        }


        echo json_encode($info);
        return $info;
    }

    public function getGooglePhotoByGId($g_id)
    {
        $apiKey = "AIzaSyBZxhxAn-PyWms-8yYb33kiRgO4cFi8o1Y";
        $url = "https://www.googleapis.com/plus/v1/people/$g_id?fields=image%2Furl&key=$apiKey";
        $res =file_get_contents($url);
        $link = json_decode($res)->image->url;
        return $link;
    }

    public function getCurrentUserInfo()
    {
        $id = Session::get('id');
        $userInfo = Array();
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
                where user.id='$id'
SQL;
        $userInfo = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        if (count($userInfo) === 0) {
            return null;
        }
        return $userInfo[0];
    }

    public function getInfoFB($fb_id)
    {
        try {
            $request = <<<TANIA
                select * from user
                where fb_id='$fb_id'
TANIA;
            $var = $this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            if (isset($var[0]))
                return $var[0];
            else return null;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function getInfoGM($gm_id)
    {
        try {
            $request = <<<TANIA
            select * from user
            where gm_id='$gm_id'
TANIA;

            $var = $this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var[0];
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function getIdFB($fb_id)
    {
        try {
            $request = <<<TANIA
                select id from user
                where fb_id='$fb_id'
TANIA;
            $var = $this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            if (isset($var[0]['id'])) {
                return $var[0]['id'];
            } else return null;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function getIdGM($gm_id)
    {
        try {
            $request = <<<TANIA
            select id from user
            where gm_id='$gm_id'
TANIA;

            $var = $this->db->query($request)->fetchAll(PDO::FETCH_ASSOC);
            return $var[0]['id'];
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }


    public function getOurTeacher()
    {
        $sql = "Select u.name, u.id, u.surname,
uu.id as uu
        from 'user' as u
        INNER JOIN role as r ON
        r.id = u.role_id
        LEFT JOIN 'unconfirmed_user' as uu ON
        uu.id=u.id
        WHERE r.title='teacher'";
        try {
            $date = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            for ($var = 0; $var < count($date); ++$var) {
                if (!isset($date[$var]['uu'])) {
                    array_push($result, $date[$var]);
                }
            }
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }

    }

    public function checkUnconfirmed($id)
    {
        try {
            $sql = <<<sql
                select * from unconfirmed_user where id='$id'
sql;
            $date = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            return count($date);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function getInfo($lessonId)
    {
        $r = <<<HERE
            SELECT
            `lesson`.`lesson_info` as lesson_info
             from lesson
            WHERE `lesson`.`id` = $lessonId
HERE;

        try {
            $request = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
            return $request;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function newInfo($lessonId, $value)
    {
        try {
            $STH = $this->db->prepare("UPDATE lesson SET lesson_info = :value WHERE id=:id");
            $STH->execute(array('value' => $value, 'id' => $lessonId));
        } catch (PDOException $e) {
            echo $e->getMessage();
        }


    }

    public function saveTask($studentId, $name, $lessonId)
    {
        try {
        $r = <<<CHECKING
            SELECT * FROM `result` WHERE `result`.`owner`=$studentId AND `result`.`lesson_id`=$lessonId;
CHECKING;
            $request = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
            if(isset($request[0]['link'])){
                unlink(HOMEWORK_FOLDER.'/'.$request[0]['link']);
                $r=<<<DELETE
                UPDATE `result` SET `link`="$name" WHERE `result`.`owner`=$studentId AND `result`.`lesson_id`=$lessonId;
DELETE;
               $this->db->query($r);
                return $request;
            }
        $r = <<<HERE
            INSERT INTO `result` (owner, link,lesson_id) VALUES ($studentId,'$name',$lessonId);
HERE;

            $request = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
            return $request;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function loadTasks($lessonId)
    {
        $r = <<<HERE
            SELECT
            `result`.`link` as link,
            `result`.`grade` as grade,
            `result`.`link` as link,
            `result`.`appraiser` as teacher,
            `result`.`apprais_time` as time,
            `result`.`recense` as recense,
            `result`.`id` as id,
             `user`.`name` as name,
             `user`.`surname` as surname,
             `user`.`fb_id` as fb_id,
             `user`.`gm_id` as gm_id
            from `user`, `result`
            WHERE `result`.`owner`=`user`.`id` AND `result`.`lesson_id` = $lessonId
HERE;

        try {
            $request = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
            return $request;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    function setDeadLine($id, $deadline)
    {
        $r = <<<SETDAD
            UPDATE `lesson`
            SET `deadline`="$deadline"
            WHERE `lesson`.`id`=$id;
SETDAD;
        $this->db->query($r);
    }

    function getDeadLine($id)
    {
        $r = <<<GETDEAD
            SELECT `deadline`
            FROM `lesson`
            WHERE `lesson`.`id`='$id';
GETDEAD;
        $var = $this->db->query($r)->fetchAll(PDO::FETCH_ASSOC);
        if (!is_null($var[0]['deadline'])) {
            return $var[0]['deadline'];
        } else return "Нет";
    }

    function grade($teacherId, $lessonId, $grade)
    {
        $time = date("d-m-Y H:i");
        $r = <<<SETGRADE
         UPDATE `result`
            SET `appraiser`='$teacherId',
            `grade`='$grade',
            appraise_time = $time
            WHERE `result`.`id`=$lessonId;
SETGRADE;
        try {
            $this->db->query($r);
        }
        catch  (PDOException $e) {
            echo $e->getMessage();
        }

    }

    // methods to dispatch URL
    public function dispatcher($controller)
    {
        $this->setLogoutLink();
        $this->checkLogout($controller);
        $this->checkUnconf();
        $this->checkStatus();
        $this->checkId($controller);
        $this->checkRoute($controller);
    }

    public function logout()
    {
        setcookie (session_id(), "", time() - 25*3600);
        session_destroy();
        session_write_close();
        header("location:".URL);
        exit;
    }

    private function checkController($controller)
    {
        return
            $controller==='calendar'||
            $controller==='grouppage'||
            $controller==='groups'||
            $controller==='lesson';
    }

    private function checkStatus()
    {
        if(!Session::has('status')) {
            Session::set("status",'not');
        }
    }

    private function checkLogout($controller)
    {
        if($controller=='logout') {
            $this->logout();
        }
    }

    private function setLogoutLink()
    {
        if(!Session::has("logout_link")) {
            Session::set('logout_link',URL."app/logout");
        }
    }

    private function checkUnconf()
    {
        if(Session::has('status') && Session::get('status')!="not" && (Session::has('id'))) {
            if(!$this->checkUnconfirmed(Session::get('id'))) {
                Session::set('status','ok');
            } else{
                Session::set('status','unconfirmed');
            }
        }
    }

    private function changeLocation($location = '') {
        header("Location:".URL.$location);
        exit;
    }

    private function checkId($controller) {
        if((Session::get('status')!='not') && (Session::get('status')!='regist')&&$this->checkController($controller)) {
            if (Session::has('id')) {
                $userInfo = $this->getCurrentUserInfo(Session::get('id'));
                if ($userInfo === null) {
                    $this->logout();
                }
            } else {
                $this->logout();
            }
        }
    }

    private function checkRoute($controller)
    {
        if(Session::has('status')){
            $status = Session::get('status');
            switch($status){
                case 'not':
                    if($this->checkController($controller)&& $controller != 'index') {
                        $this->changeLocation();
                    }
                    break;
                case 'regist':
                    if($controller!='regist'&&$controller!='sendermail') {
                        $this->changeLocation("app/regist");
                    }
                    break;
                case 'unconfirmed':
                    if($controller!="index"&&$controller!="logout"&&$controller!='sendermail') {
                        $this->changeLocation();
                    }
                    break;
                case 'ok':
                    if($controller=='index'||$controller=='regist') {
                        $this->changeLocation("app/calendar");
                    }
                    break;
            }
        } else {
            if($controller!="index" && $this->checkController($controller)) {
                header("Location:" . URL);
                exit;
            }
        }
    }
}