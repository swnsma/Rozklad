<?php

class Request {
    private static $instance = null;

    private $data = array();
    private $controller = null;
    private $action = null;

    function __construct() {
        $this->parseGet();
    }

    private function parseGet() {
        if (isset($_GET['url'])) {
            $url = explode('/', rtrim($_GET['url'], '/'));
            $this->controller = $url[0];
            if (isset($url[1])) {
                $this->action = $url[1];
                for($i = 2; $i<=count($url)-1; $i+=2) {
                    if (isset($url[$i+1])) {
                        $this->data[$url[$i]] = $url[$i+1];
                    } else {
                        $this->data[$url[$i]] = null;
                    }
                }
            } else {
                $this->action = 'index';
            }
        } else {
            $this->controller = 'index';
            $this->action = 'index';
        }
    }

    function getParam($name){
        return $this->data[$name];
    }

    function getAction(){
        return $this->action;
    }

    function getController(){
        return $this->controller;
    }

    function getParams() {
        return $this->data;
    }

    private function  __clone(){

    }

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new Request();
        }
        return self::$instance;
    }
}
?>