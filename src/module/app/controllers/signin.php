<?php

class Signin extends Controller {
    public static $status="not";
    public function __construct() {
        parent::__construct();
    }
    public function index(){
        $this->view->renderHtml("signin/index");
    }
    public function back_signin(){
       Session::set('status', 'not');
        header("Location:".Session::get('logout_link'));
        exit;
    }
}
?>