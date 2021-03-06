<?php

class Bootstrap extends Controller
{
    private $model;
    public function __construct()
    {
        Session::init(3600 * 24, 'MYSES');
        new Base_Install();
        $request = Request::getInstance();
        $url = $request->getUrl();

        if(preg_match('/grouppage/', $url)||preg_match('/groups/', $url)){
            Session::set('unusedLink',$url);
        }

        $this->model = $this->loadModel('user');
        if (is_null($this->model)) {
            $this->error();
        } else {
            $this->model->dispatcher($request->getController());
            $this->runController($request->getModule(), $request->getController(), $request->getAction());
        }
    }

   private function runController($module, $controller, $action)
   {
       $file = DOC_ROOT . $module . '/controllers/' . $controller . '.php';
       if (file_exists($file)) {
           require_once $file;
           $c = new $controller;
           if ($c->run($action)) return;
       }
       $this->error();
   }

    private function error() {
        require_once DOC_ROOT . 'app/controllers/error.php';
        $c = new Error();
        $c->run('error_404');
    }

}