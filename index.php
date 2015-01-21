<?php

require_once 'libs/Rozklad.php';
require_once 'libs/Controller.php';
require_once 'libs/Model.php';
require_once 'libs/View.php';
require_once 'libs/database.php';

$app = new Rozklad();

try {
    $db = new DataBase();
} catch(PDOException $e) {
    print 'erty';
}


?>