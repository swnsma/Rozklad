<?php
require_once DOC_ROOT . "core/Mail.php";

class SenderMail extends Controller
{
    function index()
    {
        $model = $this->loadModel('index');
        $data = 'hi';
    }

    public function sendLetter()
    {
        $model = $this->loadModel('admin');
        $adminMail=$model->getAdminMail();
        print_r($adminMail);
        if($adminMail) {
            $data = Request::getPost('data');
            $name = Request::getPost('name');
            $surname = Request::getPost('surname');
            $phone = $data['phone'];
            $email = Session::get('email');
            $m = Mail::getInstance();
            $template = $m->getTemplate('letterToAdmin2', array(
                'userName' => $name.' '.$surname,
                'phone' => $phone,
                'email' => $email,
                'mail_background' => 'mail_background',
                'url' => URL.'app/admin',
                'date'=> date("d.m.Y H:i")
            ));

            if (is_null($template)) {
                echo 'template is not exists';
            } else {
                $m->addFileToHtml(DOC_ROOT . 'public/img/mail_background2.jpg', 'mail_background');
                $m->send(array(
                    $adminMail
                ), 'Новый пользователь', $template);
            }
        }
    }

    public function sendLetterToTeacher()
    {
        $req = Request::getInstance();
        $id = $req->getParam(0);
        $model = $this->loadModel('user');
        $name = $model->getUserInfo($id);
        print_r($name);
        if(is_null($name['key'])||!$name['key']){
            $m = Mail::getInstance();
            $template = $m->getTemplate('letterToTeacher2', array(
                'userName' => $name['name'].' '.$name['surname'],
                'mail_background' => 'mail_background',
                'url' => URL.'app/calendar',
                'date'=> date("d.m.Y H:i")
            ));
            if (is_null($template)) {
                echo 'template is not exists';
            } else {
                $m->addFileToHtml(DOC_ROOT . 'public/img/mail_background2.jpg', 'mail_background');
                if ($m->send(array( $name['email']), 'Регистрация завершена', $template));
            }
        }
    }
}
