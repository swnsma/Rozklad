<?php

abstract class Controller {
    public function __construct() {
        $this->view = new View();

    }

    public function loadModel($name) {
        $path = DOC_ROOT . 'module/' . Request::getInstance()->getModule() . '/model/' . $name . '_model.php';
        if (file_exists($path)) {
            require_once $path;
            $modelName = ucfirst($name) . 'Model';
            return new $modelName;//::getInstance();
        }
        return null;
    }

    public function run($actionName = 'index') {
        $this->$actionName();
    }
    public function logout(){
//        $_SESSION['status']='not';
//        header("Location:".$_SESSION['logout_link']);
        setcookie (session_id(), "", time() - 3600);
        session_destroy();
        session_write_close();
        header("location:".URL."app/signin");
        exit;
    }

    public function getClassName(){
        return mb_strtolower(get_class($this));
    }
}

?>