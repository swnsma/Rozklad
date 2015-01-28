<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 1/28/2015
 * Time: 3:53 PM
 */
class GroupPage extends Controller {
    public function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('grouppage');
    }

    public function index() {
        $data = 'hi'; //викликаємо портрібні функції поделі
        $this->view->renderHtml('grouppage/index', $data);
    }

}