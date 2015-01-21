<?php

require_once 'core/Rozklad.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/View.php';
require_once 'core/database.php';

$app = new Rozklad();

/* приклад роботи з базою
try {
    $db = new DataBase();
    $db->query("INSERT INTO role (title) VALUES ('teacher')");
    print_r($db->query('SELECT * from role')->fetchAll(PDO::FETCH_ASSOC));
} catch(PDOException $e) {
    print 'error';
}
*/


?>