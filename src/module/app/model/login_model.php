<?php

class LoginModel extends Model
{
    public function checkName($name)
    {
        return true;
    }

    public function checkSurname($surname)
    {
        return true;
    }

    public function checkEmail($email)
    {
        return true;
    }

    public function checkPhone($phone)
    {
        return true;
    }

    public function checkToken($token)
    {
        return true;
    }

    public function checkRoleId($token)
    {
        return true;
    }

    public function getUserIdFromToken($token, $service)
    {
        try {
            if ($service == 'google') {
                $r = $this->db->prepare('SELECT `id` FROM `user` WHERE `open_id_g` = :token');
            } else {
                $r = $this->db->prepare('SELECT `id` FROM `user` WHERE `open_id_fb` = :token');
            }
            $r->execute(array(
                ':token' => $token
            ));
            $data = $r->fetchAll();
            return count($data) > 0;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function login($id)
    {
        try {
            $key = $this->generateKey();
            $r = $this->db->prepare('UPDATE `user` SET `key` = :key WHERE `id` = :id');
            $r->execute(array(
                ':key' => $key,
                ':id' => $id
            ));
            $request = Request::getInstance();
            $request::setCookie('key', $key);
            $request::setCookie('id', $id);
            return $r->rowCount() > 0;
        } catch(PDOException $e) {
            return false;
        }
    }

    private function generateKey($length = 10)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $l = strlen($chars);
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $chars[rand(0, $l - 1)];
        }
        return $key;
    }

    public function register($name, $surname, $email, $phone, $token, $service)
    {
        if ($service == 'google') {
            $token_g = $token;
            $token_f = '';
        } else {
            $token_f = $token;
            $token_g = '';
        }
        $request =<<<HERE
            INSERT INTO `user` (
                `name`,
                `surname`,
                `email`,
                `phone`,
                `open_id_g`,
                `open_id_fb`
            ) VALUES (
                :name,
                :surname,
                :email,
                :phone,
                :token_g,
                :token_f
            )
HERE;
        try {
            $r = $this->db->prepare($request);
            $r->execute(array(
                ':name' => $name,
                'surname' => $surname,
                ':email' => $email,
                ':phone' => $phone,
                ':token_g' => $token_g,
                ':token_f' => $token_f
            ));
            return $this->db->lastInsertId();
        } catch(PDOException $e) {
            return null;
        }
    }
}


?>