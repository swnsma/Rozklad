<?php

<<<<<<< Updated upstream
require '../libs/Controller.php';

=======
>>>>>>> Stashed changes
class Error extends Controller {
    function __construct() {
        parent::__construct();
        $this->view->render('error/index');
    }
}

?>