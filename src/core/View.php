<?php

class View{
    public function __construct() {}

    public function renderHtml($name, $data = null) {
        $path = FILE . 'module/' . Request::getInstance()->getModule() . '/view/'. $name . '.phtml';
        if (file_exists($path)) {
            require_once $path;
        }
    }

    public function renderJson($data) {
        header('Content-Type: application/json');
        print json_encode($data);
    }

    function renderAllHTML($page, $data = null, $files = array()) {
        require_once FILE . 'module/app/model/user_model.php';
        $user = (new UserModel)->getInfo($_SESSION['fb_ID'])[0];
        $header_data['name'] = $user['name'] . ' ' . $user['surname'];
        $header_data['status'] = $user['role_id'];
        $header_data['photo'] = 'http://graph.facebook.com/'.$user['fb_id'] . '/picture?type=large';
        $header_data['title'] = isset($data['title']) ? $data['title'] : 'default title';
        $header_data['files'] = $files;
        $this->renderHtml('common/header', $header_data);
        $this->renderHtml($page, $data);
        $this->renderHtml('common/footer');
    }
}

?>