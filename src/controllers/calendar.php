<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 17:55
 * */

class Calendar extends Controller {
    public static $role='teacher';
    function __construct() {
        parent::__construct();
        $data = $this->loadModel('calendar');
        $this->view->render('calendar/index', $data);
    }
}
?>