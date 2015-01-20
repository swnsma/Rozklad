<?php

<<<<<<< Updated upstream
require '../libs/Controller.php';

=======
>>>>>>> Stashed changes
class Index extends Controller {
    function __construct() {
        parent::__construct();
        $this->view->render('index/index');
    }
}

?>