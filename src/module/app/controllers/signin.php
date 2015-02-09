<?php

class Signin extends Controller {
    public static $status="not";
    public function __construct() {
        parent::__construct();
    }
    public function index(){
        $this->view->renderHtml("signin/index");
    }
}