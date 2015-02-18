<?php

class SenderMail extends Controller {
    public function __construct() {
        parent::__construct();
    }

    function index() {
        $model = $this->loadModel('index');
        $data = 'hi';

    }
    public function sendLetter(){
        print_r("hello");
        $data=$_POST['data'];
        $name =$data['name'];
        $surname =$data['surname'];
        $phone =$data['phone'];
        $email=Session::get('email');
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
                'swnsma@gmail.com'///доробити!
            ), 'Новый пользователь', $template);
        }
    }
}
