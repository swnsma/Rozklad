<?php

require_once 'conf/conf.php';
require_once 'core/DataBase.php';
require_once 'core/Bootstrap.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/View.php';
require_once 'core/BaseInstall.php';
require_once 'core/Request.php';
require_once 'core/Session.php';


if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

Base_Install::Run();
//Base_Install::LoadDummy();

$app = new Bootstrap();



?>