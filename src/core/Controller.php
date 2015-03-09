<?php

abstract class Controller
{
    public function __construct()
    {
        $this->view = new View();
    }

    public function loadModel($name)
    {
        $path = DOC_ROOT . Request::getInstance()->getModule() . '/model/' . $name . '_model.php';
        if (file_exists($path)) {
            require_once $path;
            $modelName = ucfirst($name) . 'Model';
            return new $modelName;
        }
        return null;
    }

    public function run($actionName = 'index')
    {
        $this->$actionName();
    }

    public function logout()
    {
        setcookie (session_id(), "", time() - 25*3600);
        session_destroy();
        session_write_close();
        header("location:".URL);
        exit;
    }

    public function getClassName()
    {
        return mb_strtolower(get_class($this));
    }
}