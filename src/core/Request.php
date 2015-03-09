<?php

class Request
{
    private static $instance = null;

    private $data = array();
    private $controller = 'index';
    private $action = 'index';
    private $module = 'app';

    private function __construct()
    {
        $this->parseGet();
    }

    private function parseGet()
    {
        $urlString = $this->getUrl();
        if (isset($urlString))
        {
            $url = explode('/', rtrim($urlString, '/'));
            $this->module = $url[0];
            if (isset($url[1]))
            {
                $this->controller = $url[1];
                if (isset($url[2]))
                {
                    if(!preg_match("/id[0-9]+/", $url[2]))
                    {
                        $this->action = $url[2];
                        $this->data = array_slice($url, 3);
                    }else{
                        $this->data[0]=preg_replace("/id/", "", $url[2]);
                    }
                }
            }
        }
    }

    public function getUrl()
    {
        if(isset($_GET['url'])) {
            return $_GET['url'];
        } else {
            return null;
        }
    }

    public function getParam($index)
    {
        if (isset($this->data[$index])) return $this->data[$index];
        return null;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getParams()
    {
        return $this->data;
    }

    public static function getCookie($key)
    {
        return $_COOKIE[$key];
    }

    public static function setCookie($key, $value)
    {
        SetCookie($key, $value, time()+3600, '/');
    }

    private function  __clone() {}

    public static function getPostArr()
    {
        return $_POST;
    }

    public static function getPost($name)
    {
        if(isset($_POST[$name]))
        {
            return $_POST[$name];
        }
        return NULL;
    }

    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new Request();
        }
        return self::$instance;
    }
}

?>