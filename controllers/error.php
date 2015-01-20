<?php

require '../libs/Controller.php';

class Error extends Controller {
    function __construct() {
        parent::__construct();
        $this->view->render('error/index');
    }
}

?>