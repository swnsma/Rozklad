<?php

class Rozklad {
    function __construct() {
        if (isset($_GET['url'])) {
            $url = explode('/', rtrim($_GET['url'], '/'));

            $file = 'controllers/' . $url[0] . '.php';
            if (file_exists($file)) {
                require $file;
                new $url[0];
            } else {
                require '../controllers/error.php';
                new Error();
            }
        } else {
            require '../controllers/index.php';
            new Index();
        }
    }
}

?>