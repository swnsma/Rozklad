<?php

require_once FILE.'conf/setup.php';


class Signin extends Controller {
    public static $status="not";
    public function __construct() {
        parent::__construct();

    }
    public function index(){
//        print_r ($_COOKIE);
        $this->view->renderHtml("signin/index","not");
    }
}

?>