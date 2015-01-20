<?php

class Rozklad {
    function __construct() {
        if (isset($_GET['url'])) {
            $url = explode('/', rtrim($_GET['url'], '/'));

            $file = 'controllers/' . $url[0] . '.php';
            if (file_exists($file)) {
                require $file;
                $controller = new $url[0];
            } else {
<<<<<<< Updated upstream
                require '../controllers/error.php';
                new Error();
=======
                require 'controllers/error.php';
                $controller = new Error();
>>>>>>> Stashed changes
            }
            $controller->loadModel($url[0]);
        } else {
            require '../controllers/index.php';
            new Index();
        }
    }
}

?>