<?php

class Request {
    private static $instance = null;

    private $data = array();
    private $controller = 'index',
        $action = 'index',
        $module = 'app';

    function __construct() {
        $this->parseGet();
    }

    private function parseGet() {
        if (isset($_GET['url'])) {
            $url = explode('/', rtrim($_GET['url'], '/'));
            $this->module = $url[0];
            if (isset($url[1])) {
                $this->controller = $url[1];
                if (isset($url[2])) {
                    $this->action = $url[2];
                    $this->data = array_slice($url, 3);
                }
            }
        }
    }

    function getParam($index){
        if (isset($this->data[$index])) return $this->data[$index];
        return null;
    }

    function getAction(){
        return $this->action;
    }

    function getModule(){
        return $this->module;
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