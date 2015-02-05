<?php

require_once FILE.'conf/setup.php';


class Signin extends Controller {
    public static $status="not";
    public function __construct() {
        parent::__construct();

    }
    public function index(){
        if(isset($_SERVER['HTTP_REFERER'])){
            if(!isset($_SESSION['unusedLink']))
                $_SESSION['unusedLink']=$_SERVER['HTTP_REFERER'];
        }
        $this->view->renderHtml("signin/index","not");
    }
}

?>