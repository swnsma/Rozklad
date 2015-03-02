<?php

class AdminModel extends Model
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getAdminMail()
    {
        $filename=DOC_ROOT."module/app/view/admin/mail.txt";
        if(file_exists($filename)) {
            $f=fopen($filename, "r");
            $s= fgets($f, 128);
            return $s;
        } else {
            return "";
        }
    }

    public function setAdminMail($email)
    {
        $filename=DOC_ROOT."module/app/view/admin/mail.txt";
        $f = fopen($filename, "w");
        $email=htmlspecialchars($email);
        fputs($f, $email);
    }

    public function confirmUser($id)
    {
        try {
            $this->db->query("DELETE FROM unconfirmed_user WHERE id=$id;");
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function unConfirmUser($id) {
        try {
            $this->db->query("INSERT INTO unconfirmed_user (id) VALUES ($id)");
            $this->db->query("UPDATE user SET key='1' WHERE id=$id;");
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function getUnconfirmedUsers()
    {
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

    public function getGooglePhotoByGId($g_id)
    {
        $apiKey = "AIzaSyBZxhxAn-PyWms-8yYb33kiRgO4cFi8o1Y";
        $url = "https://www.googleapis.com/plus/v1/people/$g_id?fields=image%2Furl&key=$apiKey";
        $res =file_get_contents($url);
        $link = json_decode($res)->image->url;
        return $link;
    }

    public function getTeachers()
    {
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
            for ($i=0; $i<count($var); $i++){
                if ($var[$i]['fb_id']) {
                    $var[$i]['photoFB'] = "http://graph.facebook.com/".$var[$i]['fb_id']."/picture?width=150&height=150";
                    $var[$i]['photo'] = $var[$i]['photoFB'];
                }

                if ($var[$i]['gm_id']) {
                    $var[$i]['photoGM'] = $this->getGooglePhotoByGId($var[$i]['gm_id']);
                    $var[$i]['photo'] = $var[$i]['photoGM'];
                }
                $var[$i]['photo'] = URL . 'public/img/ge/' . rand(1, 6) . '.png';
            }
            return $var;
        } catch(PDOException $e) {
            echo $e;
            return null;
        }
    }

    public function deleteUser($id)
    {
        $query = <<<SQL
          INSERT INTO deleted_user (id, name, surname, email, phone, role_id, gm_id, fb_id)
          SELECT id, name, surname, email, phone, role_id, gm_id, fb_id
          FROM user
          WHERE user.id = $id;
SQL;
        echo $query;
        $this->db->query($query);
        $query = <<<SQL
          DELETE FROM user
          WHERE user.id = $id;
SQL;
        echo $query;
        $this->db->query($query);
    }

    public function recoverUser($id)
    {
        $query = <<<SQL
          INSERT INTO user (id, name, surname, email, phone, role_id, gm_id, fb_id)
          SELECT id, name, surname, email, phone, role_id, gm_id, fb_id
          FROM deleted_user
          WHERE deleted_user.id = $id;
SQL;
        echo $query;
        $this->db->query($query);

        $query = <<<SQL
          DELETE FROM  deleted_user
          WHERE  deleted_user.id = $id;
SQL;
        echo $query;
        $this->db->query($query);
    }
}