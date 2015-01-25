<?php
/**
 * Created by PhpStorm.
 * User: Таня
 * Date: 22.01.2015
 * Time: 17:55
 * */

class Calendar extends Controller {
    public static $role='teacher';
    public static $id=1;

    function __construct() {
        parent::__construct();
    }

    function index() {
        $model = $this->loadModel('calendar');

        $data = 'hi';

        $this->view->renderHtml('calendar/index', $data);
    }
}
?>