<?php

class Error extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->view->renderHtml('error/index', array(
            'error' => '403 Forbidden'
        ));
    }

    function error_404()
    {
        $this->view->renderHtml('error/index', array(
            'error' => '404 Not Found'
        ));
    }
}

?>