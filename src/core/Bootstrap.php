<?php

class Bootstrap {
    function __construct() {
        if (isset($_GET['url'])) {
            $url = explode('/', rtrim($_GET['url'], '/'));

            $file = __DIR__ . '/../controllers/' . $url[0] . '.php';
            if (file_exists($file)) {
                require $file;
                new $url[0];
            } else {
                require __DIR__ . '/../controllers/error.php';
                new Error();
            }
        } else {
            require __DIR__ . '/../controllers/index.php';
            new Index();
        }
    }
}

?>