<?php


require_once 'conf/conf.php';
require_once 'core/Bootstrap.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/View.php';
require_once 'core/BaseInstall.php';
require_once 'core/Request.php';
require_once 'core/Session.php';
Base_Install::DesolationBase();
Base_Install::Run();
Base_Install::LoadDummy();
$app = new Bootstrap();

?>