<?php


class Login extends Controller {
    function __construct() {
        parent::__construct();
        $this->model = $this->loadModel('login');
    }

    public function index() {
        $data = 'hi'; //викликаємо портрібні функції поделі

        $this->view->renderHtml('login/index', $data);
    }

    public function check() {
        $r = Request::getInstance();
        $id = $this->model->getUserIdFromToken($r->getParam(0), $r->getParam(1));
        if ($id == null) {
            $this->view->renderJson(array(
                'token' => $r->getParam(0),
                'status' => 'no_register'
            ));
        } else {
            $this->view->renderJson(array(
                  'token' => $r->getParam(0),
                  'status' => $this->model->login($id) === true ? 'autorized' : 'no_autorized'
            ));
        }
    }

    public function register() {
        if (isset($_POST['name'])
            && isset($_POST['surname'])
            && isset($_POST['email'])
            && isset($_POST['phone'])
            && isset($_POST['token'])
            && isset($_POST['service'])
            && isset($_POST['role_id'])
        ) {
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $token = $_POST['token'];
            $service = $_POST['service'];
            $role_id = $_POST['role_id'];
            if ($this->model->checkName($name)
                && $this->model->checkSurname($surname)
                && $this->model->checkEmail($email)
                && $this->model->checkPhone($phone)
                && $this->model->checkToken($token)
                && $this->model->checkRoleId($role_id)
            ) {
                $id = $this->model->register($name, $surname, $email, $phone, $token, $service);
                $this->view->renderJson(array(
                    'status' => $id !== null && $this->model->login($id) === true ? 'register' : 'no_register'
                ));
            } else {
                $this->view->renderJson(array(
                    'status' => 'invalid_data'
                ));
            }
        } else {
            $this->view->renderJson(array(
                'status' => 'no_data'
            ));
        }
    }

}

?>