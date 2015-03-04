<?php

class Bootstrap extends Controller
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        Session::init(3600 * 24, 'MYSES');
        new Base_Install();

        $request = Request::getInstance();
        $urla = $request->getUrl();
        if (preg_match('/grouppage/', $urla) || preg_match('/groups/', $urla)) {
            Session::set('unusedLink', $urla);
        }
        $controller = $request->getController();
        $module = $request->getModule();

        $this->model = $this->loadModel('user');
        $this->model->dispatcher($controller);
        $this->runController($request, $module, $controller);
    }

    private function runController($request, $module, $controller){
        $file = DOC_ROOT  . 'module/' . $module . '/controllers/' . $controller . '.php';
        if (file_exists($file)) {
            require_once $file;
            $c = new $controller;
        } else {
            require_once  DOC_ROOT . 'module/app/controllers/error.php';
            $c = new Error();
        }
        $c->run($request->getAction());
    }
}