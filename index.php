<?php

require_once 'libs/Rozklad.php';
require_once 'libs/Controller.php';
require_once 'libs/Model.php';
require_once 'libs/View.php';
require_once 'libs/database.php';

//$app = new Rozklad();
try {
    $db = new DataBase();
    print_r($db);
    $db->exec("CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY,
                    title TEXT,
                    message TEXT,
                    time INTEGER)");
} catch(PDOException $e) {
    print 'error';
}

?>