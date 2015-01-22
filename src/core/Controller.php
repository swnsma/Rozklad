<?php

abstract class Controller {
    function __construct() {
        $this->view = new View();
    }

    public function loadModel($name) {
        $path = __DIR__ . '/../models/'. $name . '_model.php';
        if (file_exists($path)) {
            require $path;
            $modelName = $name . '_model';
            $this->model = new $modelName();
            return  $this->model->getData();
        }
        return null;
    }
}

?>