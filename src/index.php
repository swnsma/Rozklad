<?php

require_once 'conf/conf.php';
require_once 'core/Bootstrap.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/View.php';
require_once 'core/database.php';
require_once 'core/Request.php';
require_once 'core/Cookie.php';

$app = new Bootstrap();
//try {
//$bd=DataBase::getInstance();
//    $bd->query("INSERT INTO role (title) VALUES ('teacher')");
//    print_r($bd->query('SELECT * from role')->fetchAll(PDO::FETCH_ASSOC));
//} catch(PDOException $e) {
//    print 'error';
//}
?>